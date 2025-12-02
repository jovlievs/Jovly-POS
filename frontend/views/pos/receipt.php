<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Sale $sale */

$this->title = 'Receipt #' . $sale->id;

$shopName  = Yii::$app->params['shopName'] ?? 'Shop';
$shopPhone = Yii::$app->params['shopPhone'] ?? '';
?>

<div class="receipt">

    <h2><?= Html::encode($shopName) ?></h2>
    <?php if ($shopPhone): ?>
        <p>Phone: <?= Html::encode($shopPhone) ?></p>
    <?php endif; ?>

    <p>
        Cheque №: <strong><?= $sale->id ?></strong><br>
        Date/Time: <strong><?= Yii::$app->formatter->asDatetime($sale->created_at) ?></strong>
    </p>

    <hr>

    <table class="table table-sm">
        <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Unit price</th>
            <th class="text-right">Total</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        <?php foreach ($sale->saleItems as $item): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= Html::encode($item->product->name) ?></td>
                <td class="text-right"><?= $item->quantity ?></td>
                <td class="text-right"><?= number_format($item->unit_price, 2) ?></td>
                <td class="text-right"><?= number_format($item->line_total, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4" class="text-right">Total:</th>
            <th class="text-right"><?= number_format($sale->total_amount, 2) ?></th>
        </tr>
        </tfoot>
    </table>

    <?php if (!empty($sale->note)): ?>
        <p><strong>Note:</strong> <?= Html::encode($sale->note) ?></p>
    <?php endif; ?>

    <p class="text-muted">Thank you!</p>

    <button class="btn btn-secondary" onclick="window.print()">Print</button>
</div>
