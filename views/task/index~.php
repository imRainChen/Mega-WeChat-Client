<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Task;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\searches\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '任务列表';
$this->params['breadcrumbs'][] = $this->title;
$okStatus = [
    Task::STATUS_PENDING,
    Task::STATUS_PROCESSING,
    Task::STATUS_FINISH,
    Task::STATUS_STOP,
    Task::STATUS_END,
];
?>
<div class="task-index">


    <p>
        <?= Html::a('新增任务', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php \yii\widgets\Pjax::begin(['id' => 'grid', 'timeout' => false]) ?>
    <?= widgets\Alert::widget();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover table-bordered table-striped task-table'],
        //'rowOptions' => ['style' => 'font-size:12px'],
        //   'filterModel' => $searchModel,
        'columns' => [
            'task_id',
            //'tmpl_key',
            //'pid',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) use ($okStatus) {
                    if (in_array($model->status, $okStatus)) {
                        return '<span class="glyphicon glyphicon-ok-circle" style="color: #5cb85c"></span> <span style="color: #26b840;font-weight: bold;">' .$model->getStatusLabel().'</span>';
                    } else {
                        return '<span class="glyphicon glyphicon glyphicon-exclamation-sign" style="color: #ec971f"></span> <span style="color: #ec971f;font-weight: bold;">'.$model->getStatusLabel().'</span>' . ' <a href="javascript:void(0);" class="a-danger" data-value="'.$model->information.'">详情</a>';
                    }
                }
            ],

            [
                'attribute' => 'task_name',
                'options' => ['width' => '20%'],
            ],
            // 'send_to_type',
            // 'file',
            // 'total',
            [
                'attribute' => 'total',
                'format' => 'html',
                'value' => function ($model) {
                    return "<span class=\"label label-default\">$model->total</span>";
                }
            ],
            [
                'attribute' => 'total',
                'format' => 'html',
                'label' => '进度',
                'options' => ['width' => '15%'],
                'value' => function ($model) use ($okStatus) {
                    $percentage = floor((($model->success + $model->fail) / $model->total) * 100);
                    if (in_array($model->status, $okStatus)) {
                        $class = 'progress-bar-success';
                    } else {
                        $class = 'progress-bar-warning';
                    }
                    $html = <<<html
<div class="progress" style="margin-bottom:0">
  <div class="progress-bar $class progress-bar-striped" role="progressbar" aria-valuenow="$percentage" aria-valuemin="0" aria-valuemax="100" style="width: $percentage%;">
    $percentage%
  </div>
</div>
html;
                    return $html;
                }
            ],
            [
                'attribute' => 'success',
                'label' => '成功 / 失败',
                'format' => 'html',
                'value' => function ($model) {
                    return "<span class=\"label label-success\">$model->success</span> / <span class=\"label label-danger\">$model->fail</span";
                }
            ],
            'created_at:datetime',

            // 'file_index',

            // 'information',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{control}',
                'options' => ['width' => '10%'],
                'header' => '操作',
                'buttons' => [
                    'control' => function ($url, $model, $key) use ($startActive, $stopActive, $restartActive, $endActive) {
                        $labels = [
                            ['label' => '详情', 'url' => Url::to(['view', 'id' => $model->task_id])],
                            ['label' => '开始', 'url' => Url::to(['start', 'id' => $model->task_id]), 'options' => ['class' => 'disabled'], 'linkOptions' => ['data-method' => 'post']],
                            ['label' => '暂停', 'url' => Url::to(['stop', 'id' => $model->task_id]), 'options' => ['class' => 'disabled'], 'linkOptions' => ['data-method' => 'post']],
                            ['label' => '终止', 'url' => Url::to(['end', 'id' => $model->task_id]), 'options' => ['class' => 'disabled'], 'linkOptions' => ['data-method' => 'post', 'data-confirm' => '是否确认终止？终止后任务将无法继续执行！']],
                            ['label' => '重试', 'url' => Url::to(['restart', 'id' => $model->task_id]), 'options' => ['class' => 'disabled'], 'linkOptions' => ['data-method' => 'post']],
                            ['label' => '删除', 'url' => Url::to(['delete', 'id' => $model->task_id]), 'linkOptions' => ['data-method' => 'post', 'data-confirm' => '是否确认删除？如果任务正在执行将会被终止。']],
                        ];

                        if (in_array($model->status, $startActive)) {
                            unset($labels[1]['options']);
                        }
                        if (in_array($model->status, $stopActive)) {
                            unset($labels[2]['options']);
                        }
                        if (in_array($model->status, $restartActive)) {
                            unset($labels[4]['options']);
                        }
                        if (in_array($model->status, $endActive)) {
                            unset($labels[3]['options']);
                        }

                        Html::addCssClass($options, 'btn btn-default control-btn control-btn-dropdown-toggle');
                        return ButtonDropdown::widget([
                            'label' => '更多操作',
                            'options' => $options,
                            'dropdown' => [
                                'items' => $labels,
                            ],
                        ]);
                    }
                ]
            ],

        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>

</div>
<?php
$js = <<<JS
    function init() {
        $(".a-danger").click(function(){
            var value = $(this).attr('data-value');
            $.confirm({
                title: '详情',
                cancelButton: false,
                content: value,
            });
        })
    }
    $(document).ready(function(){
        setInterval(function(){
            $.pjax.reload('#grid');
        }, 10000);
        init();
        $('#grid').on('pjax:success',function(args){
            init();
        })
    });


JS;
$this->registerJs($js, yii\web\View::POS_END);
?>