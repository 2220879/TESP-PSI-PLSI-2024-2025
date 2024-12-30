<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'name' => 'Produtos Ginásio',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'backend\modules\api\ModuleAPI',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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

        'layout' => 'main', // Define o layout padrão para o frontend como 'main'
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/login',
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST criaruser' => 'criaruser',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/user',
                    'extraPatterns' => [
                        'POST atualizaruser' => 'atualizaruser',
                        'GET dadosuserprofile' => 'dadosuserprofile',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/produto',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET produtos' => 'produtos',
                        'GET buscarpornome/{nomeProduto}' => 'buscarpornome',
                        'GET buscarportamanho/{tamanho_id}' => 'buscarportamanho',
                        'GET buscarpormarca/{marca_id}' => 'buscarpormarca',
                        'GET buscarporcategoria/{categoria_id}' => 'buscarporcategoria',
                        'GET buscarporgenero/{genero_id}' => 'buscarporgenero',
                        'GET imagens/{produto_id}' => 'imagens',
                        'GET detalhes/{id}' => 'detalhes',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{nomeProduto}' => '<nomeProduto:[\\w ]+>', //[a-zA-Z0-9_] 1 ou + vezes (char)
                        '{tamanho_id}' => '<tamanho_id:\\d+>',
                        '{marca_id}' => '<marca_id:\\d+>',
                        '{categoria_id}' => '<categoria_id:\\d+>',
                        '{genero_id}' => '<genero_id:\\d+>',
                        '{produto_id}' => '<produto_id:\\d+>',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/favorito',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET favoritos' => 'favoritos',
                        'POST atribuirprodutofavorito' => 'atribuirprodutofavorito',
                        'DELETE apagarprodutofavorito' => 'apagarprodutofavorito',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/categoria',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET categorias' => 'categorias',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/tamanho',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET tamanhos' => 'tamanhos',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/genero',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET generos' => 'generos',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/iva',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET ivas' => 'ivas',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/marca',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET marcas' => 'marcas',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/cupaodesconto',
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET cupaodesconto' => 'cupaodesconto',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/usocupao',
                    'extraPatterns' => [
                        'POST usocupao' => 'usocupao',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/carrinhocompra',
                    'extraPatterns' => [
                        'POST adicionarcarrinho' => 'adicionarcarrinho',
                        'POST diminuir' => 'diminuir',
                        'POST aumentar' => 'aumentar',
                        'DELETE apagarlinhacarrinho' => 'apagarlinhacarrinho',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/encomenda',
                    'extraPatterns' => [
                        'POST criarencomenda' => 'criarencomenda',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/fatura',
                    'extraPatterns' => [
                        'POST download' => 'download',
                    ],
                ],
            ],
        ],

    ],
    'params' => $params,
];
