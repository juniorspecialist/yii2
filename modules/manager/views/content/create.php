<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Content */

$this->title = 'Создать ресурс';
$this->params['breadcrumbs'][] = ['label' => 'Ресурсы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-create">


    <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
    }
    ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
