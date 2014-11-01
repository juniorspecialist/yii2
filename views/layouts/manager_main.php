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

AppAsset::register($this);
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
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-center'],
        'items' => [
            ['label' => 'Шаблоны', 'url' => \yii\helpers\Url::toRoute(['/manager/template/'])],
            ['label' => 'Чанки', 'url' => \yii\helpers\Url::toRoute(['/manager/chunk/'])],
            ['label' => 'ТВ-параметры', 'url' => \yii\helpers\Url::toRoute(['/manager/tv/'])],

            [
                'label' => 'Dropdown',
                'items' => [
                    ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
                    '<li class="divider"></li>',
                    '<li class="dropdown-header">Dropdown Header</li>',
                    ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
                ],
            ],

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

    <div class="container">
        <a href="#" id="hide_tree">Скрыть</a><a href="#" id="show_tree" style="display: none">Показать</a><br>
        <iframe id="tree_iframe" name="all" src="/manager/tree/" scrolling="yes" frameborder="0" style="margin-left:5px;height: 950px; width: 10%;"></iframe>

        <iframe id="main_frame" name="main" src="/manager/content/" scrolling="yes" frameborder="0" style="float: right; margin-left:0px;height: 950px; min-width: 59%; width: auto"></iframe>

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
