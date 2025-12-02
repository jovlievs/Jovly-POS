<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Product;
use common\models\Sale;

class PosController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['index', 'receipt'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $user = Yii::$app->user->identity;
                            return $user instanceof User
                                && ($user->isAdmin() || $user->isManager() || $user->isCashier());
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * POS screen with 3 baskets stored in session.
     *
     * @param int $basket
     * @return string|\yii\web\Response
     */
    public function actionIndex($basket = 1)
    {
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $session->open();

        $activeBasket = (int)$basket;
        if ($activeBasket < 1 || $activeBasket > 3) {
            $activeBasket = 1;
        }

        // Get all active products with necessary fields for autocomplete
        $products = Product::find()
            ->select(['id', 'name', 'barcode', 'stock_quantity', 'sale_price']) // Use sale_price instead of price
            ->where(['is_active' => 1])
            ->orderBy(['name' => SORT_ASC])
            ->asArray() // Return as array for better JSON encoding
            ->all();

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
            $submitType = $request->post('submitType'); // 'save' or 'sale'

            $items = [];
            if (is_array($postItems)) {
                foreach ($postItems as $row) {
                    $productId = (int)($row['product_id'] ?? 0);
                    $qty       = (float)($row['quantity'] ?? 0); // Changed to float for decimal quantities
                    if ($productId <= 0 || $qty <= 0) {
                        continue;
                    }
                    $items[] = [
                        'product_id' => $productId,
                        'quantity'   => $qty,
                    ];
                }
            }

            // Always save basket content
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

                    // Clear only this basket
                    $baskets[$activeBasket] = [];
                    $session->set('pos_carts', $baskets);

                    Yii::$app->session->setFlash('success', 'Sale completed successfully!');
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
            'sale' => $sale, // Changed back to 'sale' to match your existing view
        ]);
    }
}