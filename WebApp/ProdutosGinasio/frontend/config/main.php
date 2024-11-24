<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'name' => 'Produtos Ginásio',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'layout' => 'main.php',

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'produtosFiltrados' => 'produto/filtrar',
                'detalhesProduto' => 'produto/detalhes',
                'produto' => 'produto/index',
                'updateCliente' => 'user/update',
                'cliente' => 'user/view',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'signup' => 'site/signup',
                'login' => 'site/login',
            ],
        ],

    ],
    'params' => $params,
];
