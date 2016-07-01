<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WechatTemplate */

$this->title = '更新模板消息：' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '模板消息列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->tmpl_key]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="wechat-template-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>