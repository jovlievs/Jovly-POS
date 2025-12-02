<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\PurchaseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Purchases';
?>

<div class="purchase-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Create Purchase', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,  // IMPORTANT: Add this line
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'productName',  // Use the public property
                'value' => 'product.name',      // Display value
                'label' => 'Product',
            ],
            [
                'attribute' => 'supplierName',  // Use the public property
                'value' => function($model) {
                    return $model->supplier ? $model->supplier->name : '(not set)';
                },
                'label' => 'Supplier',
            ],
            'quantity',
            'unit_cost:decimal',
            'total_cost:decimal',
            'purchased_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>