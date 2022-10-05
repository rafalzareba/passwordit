<?php

use common\models\User;
use yii\bootstrap5\BootstrapAsset;
use yii\bootstrap5\BootstrapPluginAsset;
use yii\log\FileTarget;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'passwordit-backend',
    'name' => 'Passwordit',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'pl-PL',
    'sourceLanguage' => 'pl-PL',
    'timeZone' => 'Europe/Warsaw',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-2a23fDaF9',
            'csrfCookie' => ['httpOnly' => true, 'secure' => !YII_DEBUG],
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-passwordit',
            'cookieParams' => ['httpOnly' => true, 'secure' => !YII_DEBUG],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                JqueryAsset::class => [
                    'sourcePath' => 'lib/jquery',
                    'js' => [
                        'jquery-3.6.1.min.js'
                    ]
                ],
                BootstrapAsset::class => [
                    'css' => []
                ],
                BootstrapPluginAsset::class => [
                    'js' => []
                ],
                AssetBundle ::class => [
                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],
        'urlManager' => require(__DIR__ . '/url_manager.php')
    ],
    'params' => $params,
];
