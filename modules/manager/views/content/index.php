<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ресурсы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-index">


    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать ресурс', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'_id',
            'id',
            //'root',
            'alias',
            //'pagetitle',
            // 'description',
            // 'alias',
            // 'published',
            // 'pub_date',
            // 'content',
            // 'isfolder',
            // 'template',
            // 'menuindex',
            // 'searchable',
            // 'cacheable',
            // 'createdby',
            // 'createdon',
            // 'editedby',
            // 'deleted',
            // 'publishedon',
            // 'menutitle',
            // 'hidemenu',
            // 'parent',
            // 'introtext',

            ['class' => 'yii\grid\ActionColumn', 'template'=>'{update}'],
        ],
    ]); ?>

</div>
