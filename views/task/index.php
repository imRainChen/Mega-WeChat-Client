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

$gridView = new GridView([
    'dataProvider' => $dataProvider,
    'columns' => [
        'task_id',
        'status',
        'task_name',
        'total',
        [
            'attribute' => 'total',
            'label' => '进度',
        ],
        'success',
        'created_at',
        '操作',
    ],
]);
?>
    <div class="task-index" id="app">
        <div class="toast toast-right">
            <!--警告框-->
        </div>
        <p>
            <?= Html::a('新增任务', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <table class="table table-hover table-bordered table-striped task-table">
            <colgroup>
                <col>
                <col>
                <col width="20%">
                <col>
                <col width="15%">
                <col>
                <col>
                <col width="11%">
            </colgroup>
            <?= $gridView->renderTableHeader() ?>
            <tbody id="table-body" style="display: none;">
            <tr v-for="item in items" v-show="item.isDelete == 0" track-by="$index">
                <td>{{item.taskId}}</td>
                <td v-if="item.status == 4 || item.status == 3">
                    <span class="glyphicon glyphicon-ok-circle" style="color: #ec971f"></span>
                    <span style="color: #ec971f;font-weight: bold;">{{item.status | getStatusLabel}}</span>
                    <a href="javascript:void(0);" class="a-danger" data-value="{{item.information}}">详情</a>
                </td>
                <td v-else>
                    <span class="glyphicon glyphicon-ok-circle" style="color: #26b840"></span>
                    <span style="color: #26b840;font-weight: bold;">{{item.status | getStatusLabel}}</span>
                </td>
                <td>{{item.taskName}}</td>
                <td><span class="label label-default">{{item.total}}</span></td>
                <td>
                    <div class="progress" style="margin-bottom:0;">
                        <div class="progress-bar" :style="{width:item.percentage + '%'}"
                             :class="{'progress-bar-warning':item.status == 4 || item.status == 3,'progress-bar-success':item.status != 4 && item.status != 3,'progress-bar-striped active':item.status==1}">
                            {{item.percentage}}%
                        </div>
                    </div>
                </td>
                <td><span class="label label-success">{{item.success}}</span> / <span class="label label-danger">{{item.fail}}</span>
                </td>
                <td>{{item.createdAt}}</td>
                <td>
                    <a href="<?= Url::to(['view']) ?>&id={{item.taskId}}">详情</a>&nbsp;
                    <div class="btn-group">
                        <button id="control-{{item.taskId}}"
                                class="btn btn-default control-btn control-btn-dropdown-toggle dropdown-toggle" data-toggle="dropdown">更多操作 <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li :class="{'disabled': item.status!=5}">
                                <a href="javascript:;" v-on:click="start(item)">开始</a>
                            </li>
                            <li :class="{'disabled': item.status!=0 && item.status != 1}">
                                <a href="javascript:;" v-on:click="stop(item)">暂停</a>
                            </li>
                            <li :class="{'disabled': item.status!=0 && item.status != 1 && item.status != 4 && item.status != 5}">
                                <a href="javascript:;" v-on:click="end(item)">终止</a>
                            </li>
                            <li :class="{'disabled': item.status != 3 && item.status != 4}">
                                <a href="javascript:;" v-on:click="restart(item)">重试</a>
                            </li>
                            <li>
                                <a href="javascript:;" v-on:click="del(item)">删除</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $dataProvider->pagination,
        ]); ?>
    </div>
<?php
$startUrl = Url::to(['start']);
$stopUrl = Url::to(['stop']);
$restartUrl = Url::to(['restart']);
$endUrl = Url::to(['end']);
$deleteUrl = Url::to(['delete']);
$js = <<<JS
Vue.filter('getStatusLabel', function (value) {
    switch (value) {
        case 0:
            return '待处理';
        case 1:
            return '正在执行';
        case 2:
            return '任务完成';
        case 3:
            return '任务失败';
        case 4:
            return '中断';
        case 5:
            return '暂停';
        case 6:
            return '终止';
        default :
            return '未知状态';
    }
});

var vue = new Vue({
    el: '#app',
    data: {
        items : $models,
    },
    methods: {
        start: function (item) {
            if (item.status == 5) {
                $('#control-' + item.taskId).addClass('disabled');
                $.post('$startUrl', {
                    id : item.taskId
                }, function(result) {
                    if (result.success) {
                        item.status = 1;
                        alertMessage(result.data.message, 'alert-success', 5000);
                    } else {
                        alertMessage(result.errMsg, 'alert-warning');
                    }
                    setTimeout("ableControl(" + item.taskId + ")", 2000);
                })
            }
        },
        restart :  function (item) {
            if (item.status == 3 || item.status == 4) {
                $('#control-' + item.taskId).addClass('disabled');
                $.post('$restartUrl', {
                    id : item.taskId
                }, function(result) {
                    if (result.success) {
                        item.status = 1;
                        alertMessage(result.data.message, 'alert-success', 5000);
                    } else {
                        alertMessage(result.errMsg, 'alert-warning');
                    }
                    setTimeout("ableControl(" + item.taskId + ")", 2000);
                })
            }
        },
        stop : function (item) {
            if (item.status == 0 || item.status == 1) {
                $('#control-' + item.taskId).addClass('disabled');
                $.post('$stopUrl', {
                    id : item.taskId
                }, function(result) {
                    if (result.success) {
                        item.status = 5;
                        alertMessage(result.data.message, 'alert-success', 5000);
                    } else {
                        alertMessage(result.errMsg, 'alert-warning');
                    }
                    setTimeout("ableControl(" + item.taskId + ")", 2000);
                })
            }
        },
        end : function (item) {
            if (item.status == 0 || item.status == 1 || item.status == 4 || item.status == 5) {
                $.confirm({
                    title: '提示',
                    content: '是否确认终止？终止后任务将无法继续执行！',
                    confirm: function(){
                        $('#control-' + item.taskId).addClass('disabled');
                        $.post('$endUrl', {
                            id : item.taskId
                        }, function(result) {
                            if (result.success) {
                                item.status = 6;
                                alertMessage(result.data.message, 'alert-success', 5000);
                            } else {
                                alertMessage(result.errMsg, 'alert-warning');
                            }
                            setTimeout("ableControl(" + item.taskId + ")", 2000);
                        })
                    }
                });
            }
        },
        del : function (item) {
            $.confirm({
                title: '提示',
                content: '是否确认删除？如果任务正在执行将会被终止。',
                confirm: function(){
                    $('#control-' + item.taskId).addClass('disabled');
                    $.post('$deleteUrl', {
                        id : item.taskId
                    }, function(result) {
                        if (result.success) {
                            item.isDelete = 1;
                            alertMessage(result.data.message, 'alert-success', 5000);
                        } else {
                            alertMessage(result.errMsg, 'alert-warning');
                        }
                        setTimeout("ableControl(" + item.taskId + ")", 2000);
                    })
                },
            });
        }
    },
    ready: function() {
        $('#table-body').show();
        var url = window.location.href + '&o=' + Math.random();
        setInterval(function(){
            $.get(url, function(result) {
                if (result.success) {
                    vue.items = result.data;
                }
            });
        }, 2000);
    },
});


$(".a-danger").click(function(){
    var value = $(this).attr('data-value');
    $.confirm({
        title: '详情',
        cancelButton: false,
        content: value,
    });
})

function alertMessage (msg, cssStyle, delayTime) {
    msg = msg || "sorry，no message";
    delayTime = delayTime || 8000;
    var alertNav = $(".toast");
    var messageHtml = '<div class="alert alert-dismissible fade in ' + cssStyle + '" role="alert"><div class="ng-binding ng-scope"><strong>提示:</strong>' + msg + '</div></div>'
    alertNav.html(messageHtml);
    $(".fade:last").delay(delayTime).fadeOut('slow', function () {
        $(this).remove();
    });
};

function ableControl(id) {
    $('#control-' + id).delay(2000).removeClass('disabled');
}
JS;
$this->registerJs($js, yii\web\View::POS_END);
$this->registerJsFile('@web/js/vue.js', ['position' => yii\web\View::POS_HEAD]);
?>