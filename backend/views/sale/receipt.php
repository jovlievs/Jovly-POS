<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Sale $model */

$this->title = 'Receipt #' . $model->id;

$shopName  = Yii::$app->params['shopName'] ?? 'Jovly POS';
$shopPhone = Yii::$app->params['shopPhone'] ?? '+998 94 703 35 12';;
?>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .receipt-container {
            box-shadow: none !important;
            max-width: 100% !important;
        }
    }

    .receipt-wrapper {
        padding: 20px;
    }

    .receipt-container {
        max-width: 700px;
        margin: 0 auto;
        background: white;
        padding: 48px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 3px solid #667eea;
    }

    .receipt-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 8px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .receipt-header .shop-phone {
        color: #718096;
        font-size: 14px;
        margin-top: 4px;
    }

    .receipt-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 32px;
        padding: 16px;
        background: #f7fafc;
        border-radius: 8px;
    }

    .receipt-info-item {
        flex: 1;
    }

    .receipt-info-label {
        font-size: 12px;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .receipt-info-value {
        font-size: 16px;
        color: #1a202c;
        font-weight: 600;
    }

    .receipt-items {
        margin-bottom: 24px;
    }

    .receipt-items table {
        width: 100%;
        border-collapse: collapse;
    }

    .receipt-items thead th {
        text-align: left;
        padding: 12px 8px;
        border-bottom: 2px solid #e2e8f0;
        color: #4a5568;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .receipt-items thead th.text-right {
        text-align: right;
    }

    .receipt-items tbody td {
        padding: 14px 8px;
        border-bottom: 1px solid #f7fafc;
        color: #2d3748;
    }

    .receipt-items tbody td.text-right {
        text-align: right;
    }

    .receipt-items tbody tr:hover {
        background: #f7fafc;
    }

    .receipt-items tfoot th {
        padding: 16px 8px;
        border-top: 3px solid #667eea;
        font-size: 16px;
        color: #1a202c;
    }

    .receipt-items tfoot th.text-right {
        text-align: right;
    }

    .receipt-total {
        text-align: right;
        font-size: 24px;
        font-weight: 700;
        color: #667eea;
    }

    .receipt-note {
        margin-top: 24px;
        padding: 16px;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-left: 4px solid #667eea;
        border-radius: 6px;
    }

    .receipt-note strong {
        color: #667eea;
        font-weight: 700;
    }

    .receipt-footer {
        text-align: center;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #e2e8f0;
        color: #718096;
        font-size: 14px;
    }

    .receipt-actions {
        margin-top: 32px;
        text-align: center;
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .item-number {
        display: inline-block;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 28px;
        font-weight: 600;
        font-size: 12px;
    }
</style>

<div class="receipt-wrapper">
    <div class="receipt-container">
        <div class="receipt-header">
            <h1><?= Html::encode($shopName) ?></h1>
            <?php if ($shopPhone): ?>
                <div class="shop-phone">📞 <?= Html::encode($shopPhone) ?></div>
            <?php endif; ?>
        </div>

        <div class="receipt-info">
            <div class="receipt-info-item">
                <div class="receipt-info-label">Receipt No.</div>
                <div class="receipt-info-value">#<?= $model->id ?></div>
            </div>
            <div class="receipt-info-item" style="text-align: right;">
                <div class="receipt-info-label">Date & Time</div>
                <div class="receipt-info-value">
                    <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:M j, Y - g:i A') ?>
                </div>
            </div>
        </div>

        <div class="receipt-items">
            <table>
                <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th>Product</th>
                    <th class="text-right" style="width: 80px;">Qty</th>
                    <th class="text-right" style="width: 100px;">Price</th>
                    <th class="text-right" style="width: 120px;">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; ?>
                <?php foreach ($model->saleItems as $item): ?>
                    <tr>
                        <td>
                            <span class="item-number"><?= $i++ ?></span>
                        </td>
                        <td><?= Html::encode($item->product->name) ?></td>
                        <td class="text-right"><?= $item->quantity ?></td>
                        <td class="text-right"><?= number_format($item->unit_price, 2) ?></td>
                        <td class="text-right"><strong><?= number_format($item->line_total, 2) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="4" class="text-right">TOTAL:</th>
                    <th class="text-right">
                        <span class="receipt-total"><?= number_format($model->total_amount, 2) ?></span>
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>

        <?php if (!empty($model->note)): ?>
            <div class="receipt-note">
                <strong>Payment Info:</strong> <?= Html::encode($model->note) ?>
            </div>
        <?php endif; ?>

        <div class="receipt-footer">
            Thank you for your business! 🙏
        </div>

        <div class="receipt-actions no-print">
            <?= Html::a('← Back to Sales', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('Back to Receipts', ['receipts'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::button('🖨️ Print Receipt', [
                'class' => 'btn btn-primary',
                'onclick' => 'window.print(); return false;'
            ]) ?>
        </div>
    </div>
</div>