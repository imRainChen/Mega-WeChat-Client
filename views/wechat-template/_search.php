<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\searches\WechatTemplateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wechat-template-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tmpl_key') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'template') ?>

    <?= $form->field($model, 'template_id') ?>

    <?= $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'created_on') ?>

    <?php // echo $form->field($model, 'updated_on') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
