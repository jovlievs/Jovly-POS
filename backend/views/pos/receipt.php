<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Sale $sale */

$this->title = 'Receipt #' . $sale->id;
?>
<h1><?= Html::encode($this->title) ?></h1>

<p>
    <strong>Shop:</strong> Your Shop Name<br>
    <strong>Phone:</strong> +998 xx xxx xx xx<br>
    <strong>Date/Time:</strong> <?= Yii::$app->formatter->asDatetime($sale->created_at) ?><br>
    <strong>Sale ID:</strong> <?= $sale->id ?>
</p>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Unit price</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 1; ?>
    <?php foreach ($sale->saleItems as $item): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= Html::encode($item->product->name ?? '') ?></td>
            <td><?= $item->quantity ?></td>
            <td><?= Yii::$app->formatter->asDecimal($item->unit_price, 2) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($item->line_total, 2) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p>
    <strong>Total:</strong>
    <?= Yii::$app->formatter->asDecimal($sale->total_amount, 2) ?>
</p>

<p>
    <strong>Note:</strong>
    <?= Html::encode($sale->note ?: '-') ?>
</p>

<p>
    <?= Html::a('Back to POS', ['index'], ['class' => 'btn btn-default']) ?>
</p>
