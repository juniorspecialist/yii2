<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','gii', 'manager'],//'debug',

    //'language' => 'ru',
    //$sourceLanguage='en' и $language='ru-RU'
    'language'=>'ru-RU',
    'sourceLanguage'=>'en',

    'defaultRoute' => 'site/index',

    'modules' => [
        //'debug' => 'yii\debug\Module',

        'gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'mongoDbModel' => [
                    'class' => 'yii\mongodb\gii\model\Generator'
                ]
            ],
        ],

        'manager' => [
            'class' => 'app\modules\manager\Module',
            'defaultRoute' => 'tree',
        ],

        'admin' => [
            'class' => 'app\mudules\admin\Module',
        ],
    ],
    'components' => [

        'assetManager' => [

//            'assetMap' => [
//                'jquery.js' => '//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js',
//            ],

            'class'=>'yii\web\AssetManager',
            'linkAssets'=>true,

            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
                        'assets/js/jquery.treeview.js',
                        'assets/js/jquery.treeview.async.js',
                        'assets/js/jquery.treeview.edit.js',
                        'assets/js/tree_initial.js',
                    ],
                    'css'=> [
                        'assets/css/jquery.treeview.css'
                    ]
                ],
            ],
        ],

        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://localhost:27017/modx',
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'sdfsf45htjyui67i78ifdsdfgl;op99789odf',
            'enableCsrfValidation' => false,
            'enableCookieValidation' => true
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
//        'log' => [
//            'traceLevel' => YII_DEBUG ? 3 : 0,
//            'targets' => [
//                [
//                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['error', 'warning'],
//                ],
//            ],
//        ],

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            //'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

                //'' => 'site/index',

                //'site/index/<alias>'=>'<alias>.html',

                ['class' => 'app\components\PageUrlRule',
                    'pattern'=>'site',
                    'route' => 'site',
                    'suffix'=>'html',
                    //'controller'=>'site'
                ],

                '<controller:\w+>/<action:\w+>/'=>'<controller>/<action>',
                '<module/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            ],


        ],



        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    //$config['bootstrap'][] = 'debug';
    //$config['modules']['debug'] = 'yii\debug\Module';

    //$config['bootstrap'][] = 'gii';
    //$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
