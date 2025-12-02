<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products';
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

    .stock-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .stock-high {
        background: #d4edda;
        color: #155724;
    }

    .stock-medium {
        background: #fff3cd;
        color: #856404;
    }

    .stock-low {
        background: #f8d7da;
        color: #721c24;
    }

    .stock-out {
        background: #e2e8f0;
        color: #4a5568;
    }
</style>

<div class="product-index">
    <div class="page-header">
        <h1>📦 <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'categoryName',
                'value' => function($model) {
                    return $model->category ? $model->category->name : '(not set)';
                },
                'label' => 'Category',
            ],
            'name',
            'sku',
            'barcode',
            [
                'attribute' => 'stock_quantity',
                'format' => 'raw',
                'value' => function($model) {
                    $stock = $model->stock_quantity;
                    if ($stock <= 0) {
                        $class = 'stock-out';
                        $text = 'Out of Stock';
                    } elseif ($stock < 10) {
                        $class = 'stock-low';
                        $text = $stock . ' (Low)';
                    } elseif ($stock < 50) {
                        $class = 'stock-medium';
                        $text = $stock;
                    } else {
                        $class = 'stock-high';
                        $text = $stock;
                    }
                    return '<span class="stock-badge ' . $class . '">' . $text . '</span>';
                },
                'label' => 'Stock',
            ],
            [
                'attribute' => 'cost_price',
                'format' => ['decimal', 2],
                'label' => 'Cost Price',
            ],
            [
                'attribute' => 'sale_price',
                'format' => ['decimal', 2],
                'label' => 'Sale Price',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>