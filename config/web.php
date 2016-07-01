<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name'  => 'Mega-WeChat',
    'language' => 'zh-CN',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '123456',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => ['callback\Response', 'responseFormat'],   //定义成功和失败的响应格式
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
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'datetimeFormat' => 'php:Y-m-d H:i:s',
            'timeFormat' => 'php:H:i:s',
        ]
    ],
    'params' => $params,
    'aliases' => [
        '@widgets' => '@app/widgets',
        '@libs' => '@app/libs',
        '@Client' => '@app/libs/Client',
        '@Swoole' => '@app/libs/Swoole',
        '@Network' => '@app/libs/Network',
        '@callback' => '@app/callback',
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        //'allowedIPs' => ['*'],
    ];
}

return $config;
