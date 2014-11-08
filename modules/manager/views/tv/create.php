<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tv */

$this->title = 'ТВ-параметры';
$this->params['breadcrumbs'][] = ['label' => 'ТВ-параметры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tv-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
