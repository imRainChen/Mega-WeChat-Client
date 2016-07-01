<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WechatTemplate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wechat-template-form">
    <?= widgets\Alert::widget();?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'template')->hiddenInput()->hint('下列格式请参考 <a href="https://mp.weixin.qq.com/advanced/tmplmsg?action=faq&lang=zh_CN" target="_blank">微信模板消息接口文档</a>'); ?>

    <div class="form-group">
    <div id="jsoneditor" style="height: 700px;" data-value="<?= $model->template ?>"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'submit-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<JS
        var container = document.getElementById("jsoneditor");
        var options = {
            modes: [ 'tree', 'code'],
            onEditable : function(node) {
                switch (node.field) {
                    case 'touser' :
                        return false;
                    default:
                        return true;
                }
            }
        };
        var editor = new JSONEditor(container, options);

        if ($('#wechattemplate-template').val() == '') {
             var json = {
                "touser": "\$\{OPENID\}",
                "template_id": "",
                "url": "",
                "data": {
                    "first" : {
                        "value" : "",
                        "color" : ""
                    },
                    "keyword1" : {
                        "value" : "",
                        "color" : ""
                    },
                    "keyword2" : {
                        "value" : "",
                        "color" : ""
                    },
                    "keyword3" : {
                        "value" : "",
                        "color" : ""
                    },
                    "remark" : {
                        "value" : "",
                        "color" : ""
                    }
                }
            };
        } else {
            var json = JSON.parse($('#wechattemplate-template').val());
        }

        editor.set(json);
        editor.expandAll();
        $('#submit-btn').click(function(){
             $('#wechattemplate-template').val(editor.getText());
        });
JS;

$this->registerJs($js, yii\web\View::POS_END);
$this->registerCssFile('@web/js/jsoneditor/dist/jsoneditor.css', ['position' => yii\web\View::POS_HEAD]);
$this->registerJsFile('@web/js/jsoneditor/dist/jsoneditor.min.js', ['position' => yii\web\View::POS_HEAD]);
?>
