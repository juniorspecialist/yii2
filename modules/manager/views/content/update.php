<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = 'Обновить ресурс: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ресурсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, ];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="content-update">




    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
