<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WechatTemplate */

$this->title = '创建模板消息';
$this->params['breadcrumbs'][] = ['label' => '模板消息列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wechat-template-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>