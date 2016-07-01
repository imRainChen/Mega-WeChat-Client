<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Task */

$this->title = '新增任务';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
