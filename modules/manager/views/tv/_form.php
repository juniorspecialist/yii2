<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tv */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tv-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'caption') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'elements') ?>

    <?= $form->field($model, 'default_text') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
