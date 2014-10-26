<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Template */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'id')->textInput(['disabled'=>!$model->isNewRecord ]) ?>

    <?= $form->field($model, 'templatename') ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'content')->textarea(['cols'=>15, 'rows'=>25]) ?>
    <?php
     ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
