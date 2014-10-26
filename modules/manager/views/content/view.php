<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = 'Информация о ресурсе №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?php /*echo  Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'_id',
            'id',
            'contentType',
            'pagetitle',
            'description',
            'alias',
            'published',
            'pub_date',
            'content',
            'isfolder',
            'template',
            'menuindex',
            'searchable',
            'cacheable',
            'createdby',
            'createdon',
            'editedby',
            'deleted',
            'publishedon',
            'menutitle',
            'hidemenu',
            'parent',
            'introtext',
        ],
    ]) ?>

</div>
