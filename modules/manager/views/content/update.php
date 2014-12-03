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

    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
    }
    ?>



    <?= $this->render('_form', [
        'model' => $model,
        'tv'=>$tv,
    ]) ?>

</div>
