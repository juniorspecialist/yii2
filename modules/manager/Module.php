<?php

namespace app\modules\manager;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\manager\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        //$this->defaultRoute = 'chunk';
        // initialize the module with the configuration loaded from config.php
        //\Yii::configure($this, require(__DIR__ . '/config.php'));
    }
}
