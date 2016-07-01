<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\searches\TaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'task_id') ?>

    <?= $form->field($model, 'tmpl_key') ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'task_name') ?>

    <?= $form->field($model, 'send_to_type') ?>

    <?php // echo $form->field($model, 'file') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'success') ?>

    <?php // echo $form->field($model, 'fail') ?>

    <?php // echo $form->field($model, 'file_index') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'information') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
