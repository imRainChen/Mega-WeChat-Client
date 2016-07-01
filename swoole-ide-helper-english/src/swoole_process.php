<?php
/**
 * Swoole Process Management
 *
 * Can facilitate communication IPC communication, the child process and the main process
 * Supports standard input and output redirection, the child process echo will be sent to the pipeline, instead of the output screen
 *
 * @package Swoole
 */
class swoole_process
{
    /**
     * PID process
     *
     * @var int
     */
    public $pid;

    /**
     * Pipeline
     *
     * @var int
     */
    public $pipe;

    /**
     * @param mixed $callback callback function subprocess
     * @param bool $redirect_stdin_stdout whether to redirect the standard input and output
     * @param bool $create_pipe whether to create a pipeline
     */
    public function __construct($callback, $redirect_stdin_stdout = false, $create_pipe = true) {}

    /**
     * Write data to the pipe
     *
     * @param string $data
     * @return int
     */
    public function write($data) {}

    /**
     * Read data from the pipe
     *
     * @param int $buffer_len Maximum read length
     * @return string
     */
    public function read($buffer_len = 8192) {}

    /**
     * Exit sub-process
     *
     * @param int $code
     * @return int
     */
    public function exit($code = 0) {}

    /**
     * Execute another process
     *
     * @param string $execute_file
     * @param array $params
     * @return bool
     */
    public function exec($execute_file, $params){ }

    /**
     * Wait for the child process exits
     *
     * on success, return return PID and exit status code array('code' => 0, 'pid' => 15001)
     * on failure, return false
     *
     * @return array|bool
     */
    public static function wait() {}

    /**
     * Deamonize process
     *
     * @param bool $nochdir
     * @param bool $noclose
     */
    public static function daemon($nochdir = false, $noclose = false) {}

    /**
     * Create a message queue
     *
     * @param int $msgkey Message Queuing Key
     * @param int $mode mode
     */
    public function useQueue($msgkey = -1, $mode = 2) {}

    /**
     * Push data to the message queue
     *
     * @param string $data
     */
    public function push($data) {}

    /**
     * Pop data from the message queue
     *
     * @param int $maxsize
     * @return string
     */
    public function pop($maxsize = 8192) {}

    /**
     * Send a signal to a process
     *
     * @param int $pid
     * @param int $sig
     */
    public static function kill($pid, $sig = SIGTERM) {}

    /**
     * Register signal handler function
     *
     * require swoole 1.7.9+
     *
     * @param int $signo
     * @param mixed $callback
     */
    public static function signal($signo, $callback) {}

    /**
     * Start the process
     *
     * @return int
     */
    public function start() {}

    /**
     * Rename the process name
     *
     * @param string $process_name
     */
    public function name($process_name) {}
}
