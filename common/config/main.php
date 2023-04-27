<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\redis\Cache::class,
        ],
        'elasticsearch' => [
            'class' => \yii\elasticsearch\Connection::class,
            'nodes' => [
                ['http_address' => 'localhost:19200'],
            ],
        ],
    ],
];
