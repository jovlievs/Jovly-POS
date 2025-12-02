<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Product;
use common\models\Sale;
use common\models\User;
use yii\filters\AccessControl;

class PosController extends Controller
{
    /**
     * POS screen with 3 baskets stored in session.
     *
     * @param int $basket Active basket number (1..3)
     */
    public function actionIndex($basket = 1)
    {
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $session->open();

        // clamp basket between 1 and 3
        $activeBasket = (int)$basket;
        if ($activeBasket < 1 || $activeBasket > 3) {
            $activeBasket = 1;
        }

        // products for dropdown
        $products = Product::find()
            ->where(['is_active' => 1])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        // load baskets from session or init
        $baskets = $session->get('pos_carts', [
            1 => [],
            2 => [],
            3 => [],
        ]);

        for ($i = 1; $i <= 3; $i++) {
            if (!isset($baskets[$i]) || !is_array($baskets[$i])) {
                $baskets[$i] = [];
            }
        }

        if ($request->isPost) {
            $activeBasket = (int)$request->post('activeBasket', $activeBasket);
            if ($activeBasket < 1 || $activeBasket > 3) {
                $activeBasket = 1;
            }

            $postItems  = $request->post('items', []);
            $note       = trim($request->post('note', ''));
            $submitType = $request->post('submitType');  // 'save' or 'sale'

            // normalize posted items
            $items = [];
            if (is_array($postItems)) {
                foreach ($postItems as $row) {
                    $productId = (int)($row['product_id'] ?? 0);
                    $qty       = (int)($row['quantity'] ?? 0);
                    if ($productId <= 0 || $qty <= 0) {
                        continue;
                    }
                    $items[] = [
                        'product_id' => $productId,
                        'quantity'   => $qty,
                    ];
                }
            }

            // always save basket content to session
            $baskets[$activeBasket] = $items;
            $session->set('pos_carts', $baskets);

            if ($submitType === 'save') {
                Yii::$app->session->setFlash('success', "Basket #{$activeBasket} saved.");
                return $this->redirect(['index', 'basket' => $activeBasket]);
            }

            if ($submitType === 'sale') {
                if (empty($items)) {
                    Yii::$app->session->setFlash('error', 'No items in active basket.');
                    return $this->redirect(['index', 'basket' => $activeBasket]);
                }

                try {
                    $sale = Sale::createFromCart($items, $note);
                    if ($sale === null) {
                        Yii::$app->session->setFlash('error', 'Could not create sale.');
                        return $this->redirect(['index', 'basket' => $activeBasket]);
                    }

                    // clear only this basket
                    $baskets[$activeBasket] = [];
                    $session->set('pos_carts', $baskets);

                    return $this->redirect(['receipt', 'id' => $sale->id]);
                } catch (\Throwable $e) {
                    Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                    return $this->redirect(['index', 'basket' => $activeBasket]);
                }
            }

            return $this->redirect(['index', 'basket' => $activeBasket]);
        }

        $currentItems = $baskets[$activeBasket] ?? [];

        return $this->render('index', [
            'products'     => $products,
            'activeBasket' => $activeBasket,
            'currentItems' => $currentItems,
        ]);
    }

    /**
     * Receipt page
     */
    public function actionReceipt($id)
    {
        $sale = Sale::find()
            ->with('saleItems.product')
            ->where(['id' => $id])
            ->one();

        if (!$sale) {
            throw new NotFoundHttpException('Sale not found.');
        }

        return $this->render('receipt', [
            'sale' => $sale,
        ]);
    }
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'receipt'], // later maybe 'history'
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // must be logged in
                        'matchCallback' => function () {
                            $user = Yii::$app->user->identity;
                            if (!$user instanceof User) {
                                return false;
                            }
                            // which roles can use POS?
                            return $user->isAdmin()
                                || $user->isManager()
                                || $user->isCashier();
                        },
                    ],
                ],
            ],
        ];
    }

}
