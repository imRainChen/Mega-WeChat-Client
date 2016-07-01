<?php
/**
 * Created by PhpStorm.
 * User: rain1
 * Date: 2016/5/7
 * Time: 15:19
 */

namespace Client;

use app\models\Task;
use Monolog\Handler\StreamHandler;
use Network\BooleanCommand;
use Network\CommandException;
use Network\HttpStatus;
use Network\SendCommand;
use Network\WechatDecoder;
use Network\WechatEncoder;
use Swoole\Util\Console;
use yii\db\Connection;
use Yii;
use yii\db\Query;
use Monolog\Logger;

class TaskClient
{
    const OPENID_MAX_LENGTH = 28;   //微信openid最大长度

    public $logger;
    public $taskLogger;
    /** @var \swoole_client */
    private $client;
    private $host;
    private $port;
    private $timeout = 1000 * 10;
    // 超时计时器
    private $timer;
    // 监控计时器，负责更新数据
    private $monitorTimer;

    // 任务数据
    private $taskId;
    private $pid;
    private $key;
    private $total = 0;
    private $count = 0;
    private $index = 0;
    private $success = 0;
    private $fail = 0;
    private $file;
    private $status;
    private $isChange = false;

    private $fileHandle;
    /** @var \SplQueue */
    private $openidList;
    /** @var Connection */
    private $db;

    private $opaque = 1;
    private $decoder;
    private $encoder;

    public function __construct($taskId)
    {
        $taskId = trim($taskId);
        $this->db = new Connection(Yii::$app->params['mega']['mysql']);
        $query = new Query();
        $task = $query->from(Task::tableName())->where(['task_id' => $taskId, 'is_delete' => Task::NOT_DELETED])->one();
        if (!$task || $task['status'] == Task::STATUS_PROCESSING || $task['status'] == Task::STATUS_FINISH) {
            throw new \InvalidArgumentException('task does not active!');
        }

        if (!file_exists($task['file'])) {
            throw new TaskFileException('task file does not exist!');
        }

        Console::setProcessName('Wechat-Task-' . $taskId);
        $this->taskId = $taskId;
        $this->pid = posix_getpid();
        $this->key = $task['tmpl_key'];
        $this->total = (int) $task['total'];
        $this->success = (int) $task['success'];
        $this->fail = (int) $task['fail'];
        $this->count = $this->total - ($this->success + $this->fail);
        $this->index = (int) $task['file_index'];
        $this->file = $task['file'];
        $this->status = (int) $task['status'];
        $this->initTaskFile();
        $this->decoder = new WechatDecoder();
        $this->encoder = new WechatEncoder();
        $logHandler = new StreamHandler(Yii::getAlias('@runtime/logs/mega-client.log'), \Monolog\Logger::INFO);
        $taskLogHandler = new StreamHandler(Yii::getAlias('@runtime/logs/task-'.$taskId.'.log'), \Monolog\Logger::ERROR);
        $this->logger = new Logger("mega-client:{$taskId}", [$logHandler]);
        $this->taskLogger = new Logger("mega-client:{$taskId}", [$taskLogHandler]);

        // 监听软中断信号
        \swoole_process::signal(SIGTERM, [$this, 'stop']);
        \swoole_process::signal(SIGINT, [$this, 'end']);
    }

    private function initTaskFile()
    {
        if ($this->fileHandle = @fopen($this->file, "r")) {
            $offset = $this->index * (self::OPENID_MAX_LENGTH + strlen(PHP_EOL));
            fseek($this->fileHandle, $offset);
        } else {
            throw new TaskFileException('task file open fail!');
        }

        $this->openidList = new \SplQueue();
        while (!feof($this->fileHandle)) {
            $line = trim(fgets($this->fileHandle));
            if ($line !== '') {
                $this->openidList->push($line);
            }
        }
    }

    public function connect($host, $port)
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        $client->set([
            'open_length_check' => true, //打开EOF检测
            'package_length_type' => 'N', //设置EOF
            'package_length_offset' => 0,
            'package_body_offset' => 4,
        ]);
        $client->on('connect', [$this, 'onConnect']);
        $client->on('receive', [$this, 'onReceive']);
        $client->on('error', [$this, 'onError']);
        $client->on('close', [$this, 'onClose']);
        $client->connect($host, $port);
        $this->host = $host;
        $this->port = $port;
        $this->timer = swoole_timer_after($this->timeout, [$this, 'onConnectTimeout']);
        $this->client = $client;
    }

    public function onConnect(\swoole_client $client)
    {
        $this->logger->info("client connect server", [__LINE__]);
        swoole_timer_clear($this->timer);
        // 更新任务进程id和任务执行中状态
        $this->saveTaskStatus(Task::STATUS_PROCESSING);
        $this->monitorTimer = swoole_timer_tick(1000, function(){
            if ($this->isChange && $this->status === Task::STATUS_PROCESSING) {
                $this->saveTaskStatus(Task::STATUS_PROCESSING);
                $this->isChange = false;
            }
        });

        $this->sendTask();
    }

    public function onReceive(\swoole_client $client, $data)
    {
        try {
            $command = $this->decode($data);
            if ($command instanceof BooleanCommand) {
                switch ($command->getCode()) {
                    case HttpStatus::SUCCESS :
                        if ($this->isAck($command->getOpaque())) {
                            $this->success++;
                            $this->isChange = true;
                        }
                        $this->sendTask();
                        break;
                    case HttpStatus::BAD_REQUEST :
                        if ($this->isAck($command->getOpaque())) {
                            $this->fail++;
                            $this->isChange = true;
                            $this->taskLogger->error('send fail', json_decode($command->getErrorMsg(), true));
                        }
                        $this->sendTask();
                        break;
                    case HttpStatus::INTERNAL_SERVER_ERROR :
                        $this->interrupt($command);
                        break;
                }
            }
        } catch (CommandException $ex) {
            $this->logger->warning("errCode:{$ex->getCode()} errMsg:{$ex->getMessage()}", [__LINE__]);
        }
    }

    public function onError(\swoole_client $client)
    {
        $this->logger->error("connect failed", [__LINE__]);
        unset($this->client);
        $this->saveTaskStatus(Task::STATUS_FAILURE, '连接服务器失败');
        $this->logger->getLogger()->flush();
        swoole_event_exit();
    }

    public function onClose(\swoole_client $client)
    {
        $this->logger->info('onClose status:' . $this->status, [__LINE__]);
        if ($this->status === Task::STATUS_PROCESSING) {
            if (isset($this->monitorTimer)) {
                swoole_timer_clear($this->monitorTimer);
            }
            $this->saveTaskStatus(Task::STATUS_INTERRUPT, '任务执行过程中被关闭，正在重试中...');
            $this->connect(Yii::$app->params['mega']['host'], Yii::$app->params['mega']['port']);
            $this->logger->warning("task:{$this->taskId} reconnect");
        }
    }

    public function onConnectTimeout()
    {
        $this->logger->warning("connection timeout", [__LINE__]);
        if (isset($this->client)) {
            $this->client->close();
        }
    }

    public function saveTaskStatus($status, $information = '')
    {
        $this->status = $status;
        return $this->db->createCommand()->update(Task::tableName(), [
            'pid' => $this->pid,
            'success' => $this->success,
            'fail' => $this->fail,
            'file_index' => $this->index,
            'status' => $status,
            'information' => $information,
            'updated_at' => time(),
        ], [
            'task_id' => $this->taskId
        ])->execute();
    }

    public function sendTask()
    {
        if ($this->count > 0) {
            try {
                $openid = $this->openidList->top();
                $command = new SendCommand($this->opaque, $openid, $this->key, null);
                $encode = $command->encode();
                if ($this->client->send($encode) === false) {
                    $this->logger->error('client send to server have failed');
                }
                //sleep(3);
            } catch (\RuntimeException $ex) {
                $this->logger->warning("errCode:{$ex->getCode()} errMsg:{$ex->getMessage()}", [__LINE__]);
            }
        } else {
            $this->finish();
        }
    }

    /**
     * 任务完成
     */
    public function finish()
    {
        $this->logger->info("finish", [__LINE__]);
        $this->saveTaskStatus(Task::STATUS_FINISH);
        swoole_event_exit();
    }

    /**
     * 任务暂停
     */
    public function stop()
    {
        $this->logger->info("receives stop signal", [__LINE__]);
        $this->saveTaskStatus(Task::STATUS_STOP);
        swoole_event_exit();
    }

    /**
     * 任务中断
     * @param BooleanCommand $command
     */
    public function interrupt(BooleanCommand $command)
    {
        $this->logger->error("errCode:{$command->getCode()} errMsg:{$command->getErrorMsg()}", [__LINE__]);
        $this->saveTaskStatus(Task::STATUS_INTERRUPT, $command->getErrorMsg());
        swoole_event_exit();
    }

    /**
     * 任务终止
     */
    public function end()
    {
        $this->logger->info("receives ending signal", [__LINE__]);
        $this->saveTaskStatus(Task::STATUS_END);
        swoole_event_exit();
    }

    public function isAck($opaque)
    {
        if ($opaque === $this->opaque) {
            $this->opaque++;
            $this->index++;
            $this->count--;
            $this->openidList->pop();
            return true;
        }
        return false;
    }

    public function decode($message, $fd = null, $server = null)
    {
        return $this->decoder->decode($message, $fd, $server);
    }

    public function encode($message)
    {
        return $this->encoder->encode($message);
    }

}