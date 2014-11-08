<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Chunk */

$this->title = 'Редактирование чанка: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Чанки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="chunk-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
