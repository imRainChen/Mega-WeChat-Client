<?php

namespace app\controllers;

use Network\BooleanCommand;
use Network\HttpStatus;
use Network\SetTableCommand;
use Yii;
use app\models\WechatTemplate;
use app\models\searches\WechatTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WechatTemplateController implements the CRUD actions for WechatTemplate model.
 */
class WechatTemplateController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all WechatTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WechatTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

//    /**
//     * Displays a single WechatTemplate model.
//     * @param string $id
//     * @return mixed
//     */
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new WechatTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WechatTemplate();

        if ($model->load(Yii::$app->request->post())) {
            $model->tmpl_key = md5(uniqid());
            if ($model->save()) {
                try {
                    if ($this->updateTemplateCache($model->tmpl_key) === false) {
                        Yii::$app->session->addFlash('danger', '更新服务器缓存失败，请重新保存！');
                        return $this->redirect(['update', 'id' => $model->tmpl_key]);
                    }
                    return $this->redirect(['index']);
                } catch(\Exception $ex) {
                    Yii::$app->session->addFlash('danger', $ex->getMessage());
                    return $this->redirect(['update', 'id' => $model->tmpl_key]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WechatTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            try {
                if ($this->updateTemplateCache($model->tmpl_key) === false) {
                    Yii::$app->session->addFlash('danger', '更新服务器缓存失败，请重新保存！');
                    return $this->redirect(['update', 'id' => $model->tmpl_key]);
                }
                return $this->redirect(['index']);
            } catch(\Exception $ex) {
                Yii::$app->session->addFlash('danger', $ex->getMessage());
                return $this->redirect(['update', 'id' => $model->tmpl_key]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing WechatTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WechatTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return WechatTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WechatTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function updateTemplateCache($key)
    {
        $client = new \swoole_client(SWOOLE_TCP, SWOOLE_SYNC);
        $client->set(array(
            'open_length_check' => true, //打开EOF检测
            'package_length_type' => 'N', //设置EOF
            'package_length_offset' => 0,
            'package_body_offset' => 4,
        ));
        $client->connect(Yii::$app->params['mega']['host'], Yii::$app->params['mega']['port'], 3);
        $command = new SetTableCommand(1, $key);
        $message = $command->encode();
        $client->send($message);
        // 接收已连接响应
        $ret = $client->recv();
        $decoder = new \Network\WechatDecoder();
        $result = $decoder->decode($ret);
        if ($result instanceof BooleanCommand) {
            if ($result->getCode() !== HttpStatus::SUCCESS) {
                return false;
            }
        }
        return true;
    }
}
