<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id') ?>


    <?= $form->field($model, 'contentType') ?>

    <?= $form->field($model, 'pagetitle') ?>

    <?= $form->field($model, 'description')->textarea(['cols'=>5, 'rows'=>5]) ?>

    <?= $form->field($model, 'alias') ?>

    <?= $form->field($model, 'published')->checkbox() ?>

    <?=$form->field($model, 'pub_date')?>

<!--    --><?//= DatePicker::widget([
//        'model' => $model,
//        'attribute' => 'pub_date',
//        'language' => 'ru',
//        'clientOptions' => [
//            'dateFormat' => 'yy-mm-dd',
//        ],
//    ]) ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'isfolder') ?>

    <?= $form->field($model, 'template')->dropDownList(\app\models\Template::getTplList()) ?>

    <?= $form->field($model, 'menuindex') ?>

    <?= $form->field($model, 'searchable') ?>

    <?= $form->field($model, 'cacheable') ?>

    <?= $form->field($model, 'createdby') ?>

    <?= $form->field($model, 'createdon') ?>

    <?= $form->field($model, 'editedby') ?>

    <?= $form->field($model, 'deleted') ?>

    <?= $form->field($model, 'publishedon') ?>

    <?= $form->field($model, 'menutitle') ?>

    <?= $form->field($model, 'hidemenu') ?>

    <?= $form->field($model, 'parent') ?>

    <?= $form->field($model, 'introtext') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
