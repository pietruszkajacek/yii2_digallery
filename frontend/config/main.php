<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'language' => 'pl',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'class' => 'frontend\components\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
			'loginUrl' => ['user/login'],
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                ['class' => 'frontend\components\NestedSetCategoryImageRule'],
                ['class' => 'frontend\components\SubmitEditImageRule'],
                ['class' => 'frontend\components\SubmitEditGalleryRule'],
//                'profile/image-submit/<imageId:(\d+)>' => 'profile/image-submit',
//                'profile/test/<url>-<id:(\d+)>' => 'profile/test'
            //'browse/<action:(images|galleries)>/<path:(\w+\/)*>' => 'browse/<action>',
            //'browse/<action:(images|galleries)>/<path:(\w+(\/)?)*>' => 'site/contact',
            //'browse/<action:(images|galleries)><path:(/(\w+))*>' => 'site/about',
            //'<controller>/<action:(\w+(\/)?)>' => '<controller>/<action>',
            //'<controller>/<action>' => '<controller>/<action>',
            //'/' => 'site/index'
            ],
        ],
    ],
    'params' => $params,
];
