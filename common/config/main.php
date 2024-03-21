<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'accesses' => [
            'class' => 'common\components\AccessesComponent',
        ],
        'infoLog' => [
            'class' => 'common\components\InfoLog',
        ],
        'api' => [
            'class' => 'common\components\Api',
        ],
    ],
];
