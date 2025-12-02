<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Tashkent',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            // ... your existing db config ...
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("SET time_zone = '+05:00'")->execute();
            },
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Asia/Tashkent', // Add this
            'defaultTimeZone' => 'Asia/Tashkent', // Add this
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
    ],
];
