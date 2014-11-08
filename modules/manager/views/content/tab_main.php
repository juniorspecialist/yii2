<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 01.11.14
 * Time: 22:41
 */
?>


<?= $form->field($model, 'pagetitle') ?>

<?= $form->field($model, 'description')->textarea() ?>

<?= $form->field($model, 'alias') ?>

<?= $form->field($model, 'template')->dropDownList(\app\models\Template::getTplList()) ?>

<?= $form->field($model, 'menuindex') ?>

<?= $form->field($model, 'menutitle') ?>

<?= $form->field($model, 'hidemenu')->checkbox() ?>

<?php

    echo $form->field($model, 'parent')->hiddenInput(['id'=>'parent_id']);

    if($model->isNewRecord){
        echo \yii\helpers\Html::label('parent_field','Не выбрано', ['parent_field']);
    }else{

        //echo $model->parentcontent->pagetitle.'<br>';
        echo \yii\helpers\Html::label('parent_field',($model->parentcontent->pagetitle==0)?'':$model->parentcontent->pagetitle, ['parent_field']);
    }







echo $form->field($model, 'content')->widget(letyii\tinymce\Tinymce::className(), [
    'options' => [
        'id' => 'testid',
        'height'=>'300px',
    ],
    'configs' => [ // Read more: http://www.tinymce.com/wiki.php/Configuration

        'language' => 'ru',
        'height'=>300,
        'plugins'=>['advlist','autolink', 'link', 'image', 'lists', 'charmap', 'hr', 'anchor', 'pagebreak', 'spellchecker',
         'searchreplace' ,'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime', 'media', 'nonbreaking',
         'save', 'table', 'contextmenu', 'directionality', 'emoticons', 'template', 'paste', 'textcolor']
    ],
]);

?>

