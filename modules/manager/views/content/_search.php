<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'id') ?>



    <?= $form->field($model, 'contentType') ?>

    <?= $form->field($model, 'pagetitle') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'alias') ?>

    <?php // echo $form->field($model, 'published') ?>

    <?php // echo $form->field($model, 'pub_date') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'isfolder') ?>

    <?php // echo $form->field($model, 'template') ?>

    <?php // echo $form->field($model, 'menuindex') ?>

    <?php // echo $form->field($model, 'searchable') ?>

    <?php // echo $form->field($model, 'cacheable') ?>

    <?php // echo $form->field($model, 'createdby') ?>

    <?php // echo $form->field($model, 'createdon') ?>

    <?php // echo $form->field($model, 'editedby') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'publishedon') ?>

    <?php // echo $form->field($model, 'menutitle') ?>

    <?php // echo $form->field($model, 'hidemenu') ?>

    <?php // echo $form->field($model, 'parent') ?>

    <?php // echo $form->field($model, 'introtext') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
