<?php
return [
    'id' => 'micro-app',
    'basePath' => __DIR__ . '/../',
    'controllerNamespace' => 'micro\controllers',
    'aliases' => [
        '@micro' => __DIR__ . '/../',
    ],
    'components' => [
        'coinCap' => [
            'class' => 'micro\components\CoinCap',
            'commission' => 0.02,
            'minValue' => 0.01
        ],
        'user' => [
            'enableSession' => false,
            'loginUrl' => null,
            'identityClass' => 'micro\models\User',
        ],
        'request' => [
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'scriptUrl' => '/index.php',
            'enablePrettyUrl' => true,
            'rules' => [
                'GET api/v1' => 'api/v1/index',
                'POST api/v1' => 'api/v1/convert',
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                /** @var yii\web\Response $response */
                $response = $event->sender;
                //replace 401 auth error response with custom response
                if ($response->statusCode == 401) {
                    $response->data = [
                        'status' => 'error',
                        'code' => 403,
                        'message”' => 'Invalid token'
                    ];
                    $response->statusCode = 200;
                    $response->format = \yii\web\Response::FORMAT_JSON;
                }
            },
        ],
    ]
];