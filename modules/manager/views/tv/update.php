<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tv */

$this->title = 'Редактирование тв-параметра: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'ТВ-параметры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="tv-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
