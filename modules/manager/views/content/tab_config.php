<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.11.14
 * Time: 22:42
 */
?>

<?= $form->field($model, 'contentType') ?>

<?= $form->field($model, 'published')->checkbox() ?>





<?= $form->field($model, 'pub_date')->widget(
    yii\jui\DatePicker::className(), [
    // inline too, not bad
    //'inline' => true,
    'language'=> 'ru',
    // modify template for custom rendering
    //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
    'clientOptions' => [
        'autoclose' => true,
        //'format' => 'Y-m-d',
        //'format' => 'php:m/d/Y',
       // ['defaultDate' => '2014-01-01']
    ]
]);?>

<?= $form->field($model, 'searchable')->checkbox() ?>

<?= $form->field($model, 'cacheable')->checkbox() ?>

<?= $form->field($model, 'createdby')->checkbox() ?>

<?= $form->field($model, 'createdon') ?>

<?= $form->field($model, 'editedby') ?>

<?= $form->field($model, 'deleted')->checkbox() ?>


<?= $form->field($model, 'publishedon')->widget(
    yii\jui\DatePicker::className(), [
    // inline too, not bad
    //'inline' => true,
    'language'=> 'ru',
    // modify template for custom rendering
    //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
    'clientOptions' => [
        'autoclose' => true,
        //'format' => 'php:m/d/Y',
        'language'=> 'ru',
        ['defaultDate' => '2014-01-01']
    ]
]);?>