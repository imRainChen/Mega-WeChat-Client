<?php

namespace app\controllers;

use app\models\TaskForm;
use app\models\User;
use Yii;
use app\models\Task;
use app\models\searches\TaskSearch;
use yii\base\InvalidParamException;
use yii\db\Exception;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use app\commands\TaskController as TaskCommand;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    public static $taskFilePath = '@app/data/task';
    public static $sliceLimit = 10000;  //每个任务多少项数据

    public $logger;

    public static $startActive = [
        Task::STATUS_STOP,
    ];

    public static $stopActive = [
        Task::STATUS_PENDING,
        Task::STATUS_PROCESSING,
    ];

    public static $restartActive = [
        Task::STATUS_FAILURE,
        Task::STATUS_INTERRUPT,
    ];

    public static $endActive = [
        Task::STATUS_PENDING,
        Task::STATUS_PROCESSING,
        Task::STATUS_INTERRUPT,
        Task::STATUS_STOP,
    ];

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'start' => ['post'],
                    'restart' => ['post'],
                    'stop' => ['post'],
                    'end' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $models = array_values($dataProvider->getModels());
        $array = [];
        foreach ($models as $model) {
            $array[] = [
                'taskId' => $model->task_id,
                'taskName' => $model->task_name,
                'total' => $model->total,
                'success' => $model->success,
                'fail' => $model->fail,
                'createdAt' => date('Y-m-d H:i:s', $model->created_at),
                'status' => $model->status,
                'information' => $model->information,
                'percentage' => floor((($model->success + $model->fail) / $model->total) * 100),
                'isDelete' => $model->is_delete
            ];
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $array;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'models' => json_encode($array),
            'startActive' => self::$startActive,
            'restartActive' => self::$restartActive,
            'stopActive' => self::$stopActive,
            'endActive' => self::$endActive,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TaskForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            try {
                $model->taskFile = UploadedFile::getInstance($model, 'taskFile');
                $dataList = [];
                switch($model->send_to_type)
                {
                    case Task::SEND_ALL_USERS:
                        $dataList = User::find()->select('openid')->column();
                    break;
                    case Task::SEND_ASSIGN_USERS :
                        $dataList = $this->findOpenidFromUsersExcel($model->taskFile);
                    break;
                    case Task::SEND_ASSIGN_OPENID :
                        $dataList = $this->getOpenidFromExcel($model->taskFile);
                    break;
                }

                if (empty($dataList)) {
                    throw new BadRequestHttpException('无任何用户可发送');
                }

                /** @var Task[] $tasks */
                $tasks = [];
                // 如果切分任务，则先把数据切分不同文件后，插入相应数据。整个切分是一个事务过程。
                if ($model->sliceTask) {
                    $transaction = Task::getDb()->beginTransaction();
                    $files = $this->sliceFile($dataList, self::$sliceLimit);
                    try {
                        foreach ($files as $key => $file) {
                            $task = new Task();
                            $task->tmpl_key = $model->tmpl_key;
                            $task->task_name = $model->task_name . '-' . ($key + 1);
                            list($task->file, $task->total) = $file;
                            $task->send_to_type = $model->send_to_type;
                            if ($task->save(false)) {
                                $tasks[] = $task;
                            } else {
                                throw new Exception('任务切分失败！');
                            }
                        }
                        $transaction->commit();
                    } catch (Exception $ex) {
                        foreach ($files as $file) {
                            unlink($file[0]);
                        }
                        $transaction->rollBack();
                    }
                } else {
                    $model->file = $this->generateTaskFile($dataList);
                    $model->total = count($dataList);
                    if ($model->save(false)) {
                        $tasks[] = $model;
                    } else {
                        unlink($model->file);
                        throw new Exception('添加任务失败！');
                    }
                }

                // 调用开始执行任务命令
                foreach ($tasks as $model) {
                    $command = TaskCommand::PHP . ' ' . Yii::getAlias('@app/yii') . ' task start ' . $model->task_id;
                    $message = exec($command, $output, $returnCode);
                    unset($output);
                    if ($returnCode) {
                        $model->status = Task::STATUS_FAILURE;
                        $model->information = '调用运行任务命令失败！ ' . $message;
                        $model->save(false);
                    }
                }

                return $this->redirect(['index']);
            } catch (\Exception $ex) {
                Yii::$app->session->addFlash('danger', $ex->getMessage());
                return $this->redirect(['create']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $file UploadedFile
     * @return array
     * @throws \PHPExcel_Exception
     */
    private function findOpenidFromUsersExcel($file)
    {
        if ($file === null) {
            throw new InvalidParamException('请上传附件');
        }

        $excel = \PHPExcel_IOFactory::load($file->tempName);
        $sheet = $excel->getSheet(0);
        $highestRowNum = $sheet->getHighestRow();

        $data = [];
        for ($row = 2; $row <= $highestRowNum; $row++) {
            $value = $sheet->getCell('A' . $row)->getValue();
            if ($value !== '') {
                $data[] = trim($value);
            }
        }

        if (!empty($data)) {
            $data = \app\models\User::find()->select('openid')->where(['uid' => $data])->column();
        }

        return $data;
    }

    /**
     * @param $file UploadedFile
     * @return array
     * @throws \PHPExcel_Exception
     */
    private function getOpenidFromExcel($file)
    {
        if ($file === null) {
            throw new InvalidParamException('请上传附件');
        }

        $excel = \PHPExcel_IOFactory::load($file->tempName);
        $sheet = $excel->getSheet(0);
        $highestRowNum = $sheet->getHighestRow();

        $data = [];
        for ($row = 2; $row <= $highestRowNum; $row++) {
            $value = $sheet->getCell('A' . $row)->getValue();
            if ($value !== '') {
                $data[] = trim($value);
            }
        }

        return $data;
    }

    private function generateTaskFile($dataList)
    {
        $taskFilePath = Yii::getAlias(self::$taskFilePath);
        if (!is_dir($taskFilePath)) {
            FileHelper::createDirectory($taskFilePath);
        }
        $filename = $taskFilePath . DIRECTORY_SEPARATOR . uniqid('wt') . Yii::$app->security->generateRandomString(10) . '.txt';
        $fp = fopen($filename, 'w');
        foreach ($dataList as $data) {
            fwrite($fp, $data . PHP_EOL);
        }
        fclose($fp);
        return $filename;
    }

    private function sliceFile($dataList, $limit = 10000)
    {
        $count = count($dataList);
        $pageCount = (int)(($count + $limit - 1) / $limit);
        $files = [];
        for ($i = 0; $i < $pageCount; $i++) {
            $offset = $i * $limit;
            $_dataList = array_slice($dataList, $offset, $limit);
            $files[] = [$this->generateTaskFile($_dataList), count($_dataList)];
        }
        return $files;
    }

    public function actionStart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if (in_array($model->status, self::$startActive)) {
            $command = TaskCommand::PHP . ' ' . Yii::getAlias('@app/yii') . ' task start ' . $model->task_id;
            $message = exec($command, $output, $returnCode);
            if ($returnCode) {
                $model->status = Task::STATUS_FAILURE;
                $model->information = '开始失败！ ' . $message;
                $model->save(false);
                throw new ServerErrorHttpException('开始失败！');
            } else {
                return [
                    'message' => '开始成功！'
                ];
            }
        }
        throw new ForbiddenHttpException('非法操作！');
    }

    public function actionRestart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if (in_array($model->status, self::$restartActive)) {
            $command = TaskCommand::PHP . ' ' . Yii::getAlias('@app/yii') . ' task start ' . $model->task_id;
            $message = exec($command, $output, $returnCode);
            if ($returnCode) {
                $model->status = Task::STATUS_FAILURE;
                $model->information = $model->information . '</br> 重试失败！ ' . $message;
                $model->save(false);
                throw new ServerErrorHttpException('重试失败！');
            } else {
                return [
                    'message' => '重试成功！.'
                ];
            }
        }
        throw new ForbiddenHttpException('非法操作！');
    }

    public function actionStop()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if (in_array($model->status, self::$stopActive)) {
            $command = TaskCommand::PHP . ' ' . Yii::getAlias('@app/yii') . ' task stop ' . $model->task_id;
            $message = exec($command, $output, $returnCode);
            if ($returnCode) {
                $model->status = Task::STATUS_FAILURE;
                $model->information = '暂停失败！ ' . $message;
                $model->save(false);
                throw new ServerErrorHttpException('暂停失败！');
            } else {
                return [
                    'message' => '暂停成功！'
                ];
            }
        }
        throw new ForbiddenHttpException('非法操作！');
    }

    public function actionEnd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if (in_array($model->status, self::$endActive)) {
            if ($model->status == Task::STATUS_STOP) {
                $model->status = Task::STATUS_END;
                $model->save(false);
            } else {
                $command = TaskCommand::PHP . ' ' . Yii::getAlias('@app/yii') . ' task end ' . $model->task_id;
                $message = exec($command, $output, $returnCode);
                if ($returnCode) {
                    $model->status = Task::STATUS_FAILURE;
                    $model->information = '终止失败！ ' . $message;
                    $model->save(false);
                    throw new ServerErrorHttpException('终止失败！');
                }
            }
            return [
                'message' => '终止成功！'
            ];
        }
        throw new ForbiddenHttpException('非法操作！');
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if ($model->status == Task::STATUS_PROCESSING) {
            $command = TaskCommand::PHP . ' ' . Yii::getAlias('@app/yii') . ' task end ' . $model->task_id;
            system($command, $returnCode);
        }

        $model->is_delete = Task::IS_DELETED;
        if (!$model->save(false)) {
            throw new ServerErrorHttpException('删除失败！');
        }

        return [
            'message' => '删除成功！'
        ];
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne(['task_id' => $id, 'is_delete' => Task::NOT_DELETED])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
