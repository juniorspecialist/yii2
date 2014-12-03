<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;




use yii\bootstrap\Nav;
use yii\bootstrap\Tabs;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

yii\bootstrap\BootstrapAsset::register($this);
yii\web\AssetBundle::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php
        if(!$model->isNewRecord){
            echo Html::a('Просмотр', \yii\helpers\Url::toRoute(['/site/index/', 'alias'=>$model->alias]),['class' =>'btn btn-success','target'=>'new_blank']);
//            echo \yii\bootstrap\Button::widget([
//                'label' => 'Просмотр',
//                'href'=>\yii\helpers\Url::toRoute(['/site/index/', 'alias'=>$model->alias]),
//                'options' => ['class' => 'btn btn-success',
//                'target'=>'new_blank',
//                ],
//            ]);
        }
        ?>
    </div>

    <h2><?= Html::encode($this->title) ?></h2>

    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Общие',
                'content' => $this->render('tab_main', ['model'=>$model, 'form'=>$form]),
                'active' => true
            ],
            [
                'label' => 'Настройки страницы',
                'content' => $this->render('tab_config', ['model'=>$model, 'form'=>$form]),
                //'headerOptions' => [...],
                'options' => ['id' => 'tab_config'],
            ],

            [
                'label' => 'Тв-параметры',
                'content' => $this->render('tab_tv_params', ['model'=>$model, 'form'=>$form, 'tv'=>$tv]),
                //'headerOptions' => [...],
                'options' => ['id' => 'tab_tv_params'],
            ],
        ],
    ]);
    ?>



    <?php ActiveForm::end(); ?>

</div>
