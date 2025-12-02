<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use common\models\User;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <style>
            body {
                background-color: #f7fafc;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }

            .navbar {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                box-shadow: 0 2px 12px rgba(0,0,0,0.1);
                padding: 12px 0;
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 20px;
                color: white !important;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .navbar-brand::before {
                content: '';
                font-size: 24px;
            }

            .navbar-nav .nav-link {
                color: rgba(255,255,255,0.9) !important;
                font-weight: 500;
                padding: 8px 16px !important;
                border-radius: 6px;
                transition: all 0.2s ease;
                margin: 0 4px;
            }

            .navbar-nav .nav-link:hover {
                background-color: rgba(255,255,255,0.15);
                color: white !important;
            }

            .navbar-nav .nav-link.active {
                background-color: rgba(255,255,255,0.2);
                color: white !important;
            }

            .nav-item form {
                margin: 0;
                padding: 0;
            }

            .logout-button {
                background: rgba(255,255,255,0.2) !important;
                border: 1px solid rgba(255,255,255,0.3) !important;
                color: white !important;
                padding: 8px 20px !important;
                border-radius: 6px !important;
                font-weight: 600 !important;
                transition: all 0.2s ease !important;
                text-decoration: none !important;
                display: inline-block !important;
            }

            .logout-button:hover {
                background: rgba(255,255,255,0.3) !important;
                border-color: white !important;
                transform: translateY(-1px);
                color: white !important;
            }

            .logout-button:focus {
                color: white !important;
                box-shadow: none !important;
            }

            .navbar-toggler {
                border-color: rgba(255,255,255,0.3) !important;
            }

            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255,255,255,1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
            }

            .container {
                max-width: 1200px;
                padding: 32px 15px;
            }

            .breadcrumb {
                background: white;
                padding: 12px 20px;
                border-radius: 8px;
                margin-bottom: 24px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }

            .footer {
                background: white;
                border-top: 1px solid #e2e8f0;
                padding: 24px 0;
                margin-top: 48px;
            }

            .footer .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                color: #718096;
                font-size: 14px;
                padding: 0 15px;
            }

            @media (max-width: 768px) {
                .footer .container {
                    flex-direction: column;
                    gap: 8px;
                    text-align: center;
                }
            }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => 'Jovly POS ',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-lg navbar-dark',
            ],
        ]);

        $menuItems = [];

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            /** @var User $currentUser */
            $currentUser = Yii::$app->user->identity;

            $menuItems[] = ['label' => 'Dashboard', 'url' => ['/site/index']];

            // Show different menus based on role
            if ($currentUser->isAdminOrManager()) {
                // Admin & Manager see full menu
                $menuItems[] = ['label' => 'Categories', 'url' => ['/category/index']];
                $menuItems[] = ['label' => 'Products', 'url' => ['/product/index']];
                $menuItems[] = ['label' => 'Suppliers', 'url' => ['/supplier/index']];
                $menuItems[] = ['label' => 'Purchases', 'url' => ['/purchase/index']];
                $menuItems[] = ['label' => 'Purchase Returns', 'url' => ['/purchase-return/index']];
                $menuItems[] = ['label' => 'Sales', 'url' => ['/sale/index']];
                $menuItems[] = ['label' => 'Receipts', 'url' => ['/sale/receipts']];

                // Users – only for admin
                if ($currentUser->isAdmin()) {
                    $menuItems[] = ['label' => 'Users', 'url' => ['/user/index']];
                }
            } elseif ($currentUser->isCashier()) {
                // Cashier only sees POS and Sales/Receipts (view only)
                $menuItems[] = ['label' => 'POS (New Sale)', 'url' => ['/pos/index']];
                $menuItems[] = ['label' => 'Sales', 'url' => ['/sale/index']];
                $menuItems[] = ['label' => 'Receipts', 'url' => ['/sale/receipts']];
            }

            // Logout button
            $menuItems[] = [
                'label' => '<form method="post" action="' . \yii\helpers\Url::to(['/site/logout']) . '" style="display:inline;margin:0;">'
                    . Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken)
                    . '<button type="submit" class="btn logout-button">Logout (' . Html::encode($currentUser->username) . ')</button>'
                    . '</form>',
                'encode' => false,
            ];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'],
            'items' => $menuItems,
        ]);

        NavBar::end();
        ?>

        <div class="container">
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div>&copy; Jovly POS <?= date('Y') ?></div>
            <div>Powered by Yii2</div>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>