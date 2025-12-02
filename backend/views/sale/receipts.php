<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\SaleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Receipts';
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e2e8f0;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #1a202c;
    }

    .receipt-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .receipt-link:before {
        content: '🧾';
        font-size: 16px;
    }

    .receipt-link:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #764ba2;
        text-decoration: none;
    }

    .payment-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .payment-card {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .payment-cash {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .payment-mixed {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }
</style>

<div class="receipts-index">
    <div class="page-header">
        <h1>🧾 <?= Html::encode($this->title) ?></h1>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        'Receipt #' . $model->id,
                        ['receipt', 'id' => $model->id],
                        ['class' => 'receipt-link']
                    );
                },
                'label' => 'Receipt',
            ],
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
                'label' => 'Date & Time',
            ],
            [
                'attribute' => 'total_amount',
                'format' => 'raw',
                'value' => function($model) {
                    return '<strong style="color: #667eea; font-size: 15px;">'
                        . number_format($model->total_amount, 2)
                        . '</strong>';
                },
                'label' => 'Total Amount',
            ],
            [
                'attribute' => 'note',
                'format' => 'raw',
                'label' => 'Payment',
                'value' => function($model) {
                    if (empty($model->note)) {
                        return '<span class="payment-badge payment-card">Card</span>';
                    } elseif (stripos($model->note, 'cash') !== false && stripos($model->note, 'card') !== false) {
                        return '<span class="payment-badge payment-mixed">' . Html::encode($model->note) . '</span>';
                    } elseif (stripos($model->note, 'cash') !== false) {
                        return '<span class="payment-badge payment-cash">' . Html::encode($model->note) . '</span>';
                    } else {
                        return '<span class="payment-badge payment-card">' . Html::encode($model->note) . '</span>';
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            '👁️ View',
                            ['receipt', 'id' => $model->id],
                            [
                                'class' => 'btn btn-sm btn-info',
                                'title' => 'View Receipt'
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div>