<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Task */

$this->title = $model->task_id;
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'task_id',
            'tmpl_key',
            'task_name',
            [
                'label' => $model->getAttributeLabel('send_to_type'),
                'attribute' => function($model) {
                    return $model->getSendToTypeLabel();
                }
            ],
            //'file',
            'total',
            'success',
            'fail',
            //'file_index',
            [
                'label' => $model->getAttributeLabel('status'),
                'attribute' => function($model) {
                    return $model->getStatusLabel();
                }
            ],
            'information',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
