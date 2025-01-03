<?php
return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => \yii\web\User::class,
            'identityClass' => 'common\models\User',
        ],
        'db' => ['class' => \yii\db\Connection::class, 'dsn' => 'mysql:host=127.0.0.1;dbname=produtosginasio_tests', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8',],
    ],
];
