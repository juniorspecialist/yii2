<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChunkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Чанки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chunk-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать чанк', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'model'=>$model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'_id',
            //'LinkUpdate',
//            [                    // the owner name of the model
//                'label' => 'title',
//                'value' => $model->LinkUpdate,
//            ],

//            [
//                'class' => 'yii\grid\DataColumn',
//                'attribute' => $dataProvider->linkupdate,
//                'format' => 'html',
//                'label' => 'Название',
//            ],
            'title',
            //'content',

            ['class' => 'yii\grid\ActionColumn', 'visible'=>true, 'template'=>'{update}'],
        ],
    ]); ?>

</div>
