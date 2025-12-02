<?php

use yii\filters\AccessControl;
use common\models\User;
//use Yii;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'defaultRoute' => 'site/login',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],

    // 🔐 GLOBAL ACCESS CONTROL – BU YERDA BO‘LISHI KERAK, components ICHIDA EMAS
    'as access' => [
        'class' => AccessControl::class,
        'except' => ['site/login', 'site/error', 'site/logout'], // logout ham ruxsat
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'], // faqat login bo'lganlar
                'matchCallback' => function ($rule, $action) {
                    /** @var User|null $user */
                    $user = Yii::$app->user->identity;
                    if (!$user instanceof User) {
                        return false;
                    }

                    $controller = $action->controller->id; // masalan: 'site', 'pos', 'sale', 'category'
                    $id         = $action->id;             // masalan: 'index', 'view', 'create', ...

                    // 🔹 site/index → hamma login bo'lganlar (admin, manager, cashier)
                    if ($controller === 'site' && $id === 'index') {
                        return true;
                    }

                    // 🔹 POS controller → admin + manager + cashier
                    if ($controller === 'pos') {
                        return $user->isAdmin() || $user->isManager() || $user->isCashier();
                    }

                    // 🔹 Sales controller:
                    // index + view → admin/manager/cashier
                    // qolgan actionlar → faqat admin/manager
                    if ($controller === 'sale') {
                        if (in_array($id, ['index', 'view'], true)) {
                            return $user->isAdmin() || $user->isManager() || $user->isCashier();
                        }
                        return $user->isAdminOrManager();
                    }

                    // 🔹 Boshqa hamma controllerlar (category, product, purchase, supplier, ...)
                    // → faqat admin + manager
                    return $user->isAdminOrManager();
                },
            ],
        ],
    ],

    'params' => $params,
];
