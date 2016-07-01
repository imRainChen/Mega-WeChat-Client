<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\WechatTemplate;
/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">

    <?= widgets\Alert::widget();?>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'task_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tmpl_key')->dropDownList(
        WechatTemplate::find()->select([new \yii\db\Expression("CONCAT(title, '：' ,tmpl_key)"), 'tmpl_key'])->indexBy('tmpl_key')->column(),
        ['prompt' => '------请选择微信模板------']
    )
    ?>

    <?= $form->field($model, 'send_to_type')->radioList([
        '0' => '所有用户',
        '1' => '指定用户',
        '2' => '指定OPENID',
    ])->hint('选择指定用户后请上传附件') ?>

    <?= $form->field($model, 'taskFile')->label('附件')->fileInput()->hint('仅支持.xls,.xlsx,.csv文件，请下载参考模板：' . Html::a('用户模板', '@web/tmpl/user_template.xlsx') . ' '  . Html::a('OPENID模板', '@web/tmpl/openid_template.xlsx')) ?>

    <?= $form->field($model, 'sliceTask')->checkbox(['label' => '切分'])->label('任务切分：')->hint('可将任务分成多个任务执行，能加快任务完成') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增任务' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
