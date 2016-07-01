<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\searches\WechatTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模板消息列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wechat-template-index">

    <p>
        <?= Html::a('创建模板消息', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tmpl_key',
            'title',
            //'template:ntext',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'Y-m-d H:i:s'],
            ],
            // 'updated_on',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
