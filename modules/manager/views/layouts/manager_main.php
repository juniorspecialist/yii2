<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.10.14
 * Time: 13:17
 */
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

//var_dump($this->registerCss());die();
yii\web\AssetBundle::register($this);
//yii\web\View::registerCssFile();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Админка',
        'brandUrl' => \yii\helpers\Url::toRoute(['/manager/']),
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'target'=>'main'
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-center'],
        'items' => [
            ['label' => 'Шаблоны', 'url' => \yii\helpers\Url::toRoute(['/manager/template/']),'linkOptions' => ['target'=>'main']],
            ['label' => 'Чанки', 'url' => \yii\helpers\Url::toRoute(['/manager/chunk/']),'linkOptions' => ['target'=>'main']],
            ['label' => 'ТВ-параметры', 'url' => \yii\helpers\Url::toRoute(['/manager/tv/']),'linkOptions' => ['target'=>'main']],

//            [
//                'label' => 'Модули',
//                'items' => [
//                    ['label' => 'Импорт', 'url' => '#','linkOptions' => ['target'=>'main']],
//                    '<li class="divider"></li>',
//                    ['label' => 'Экспорт', 'url' => '#','linkOptions' => ['target'=>'main']],
//                ],
//            ],

            /*Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']],
            */
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container1" style="border: 1px solid #008000; margin-top: 50px">

        <iframe id="tree_iframe" name="all" src="/manager/tree/tree" scrolling="yes" frameborder="0" style="border: 1px solid red;margin-left:5px;height: 950px; width: 20%;"></iframe>

        <iframe id="main_frame" name="main" src="/manager/content/" scrolling="yes" frameborder="0" style="float: right; margin-left:0px;height: 950px; min-width: 79%; width: auto"></iframe>

    </div>
</div>

<!--    <footer class="footer">-->
<!--        <div class="container">-->
<!--            <p class="pull-left">&copy; My Company --><?//= date('Y') ?><!--</p>-->
<!--            <p class="pull-right">--><?//= Yii::powered() ?><!--</p>-->
<!--        </div>-->
<!--    </footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
