<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TvSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ТВ-параметры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tv-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать тв-параметр', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'_id',
            //'type',
            //'id',
            'name',
            'caption',
            //'description',
            // 'elements',
            // 'default_text',

            ['class' => 'yii\grid\ActionColumn','template'=>'{update}'],
        ],
    ]); ?>

</div>
