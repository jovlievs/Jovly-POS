<?php

use common\models\Product;
use common\models\Sale;
use common\models\Purchase;

/* @var $this yii\web\View */

$this->title = 'Dashboard';

$totalProducts  = Product::find()->count();
$totalStock     = Product::find()->sum('stock_quantity');
$totalSales     = Sale::find()->count();
$totalPurchases = Purchase::find()->count();

?>

<style>
    .dashboard-header {
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 2px solid #e2e8f0;
    }

    .dashboard-header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 8px 0;
    }

    .dashboard-header p {
        color: #718096;
        font-size: 16px;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color-start), var(--card-color-end));
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
        border-color: var(--card-color-start);
    }

    .stat-card.products {
        --card-color-start: #667eea;
        --card-color-end: #764ba2;
    }

    .stat-card.stock {
        --card-color-start: #f093fb;
        --card-color-end: #f5576c;
    }

    .stat-card.sales {
        --card-color-start: #4facfe;
        --card-color-end: #00f2fe;
    }

    .stat-card.purchases {
        --card-color-start: #43e97b;
        --card-color-end: #38f9d7;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        font-size: 24px;
    }

    .stat-card.products .stat-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-card.stock .stat-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .stat-card.sales .stat-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .stat-card.purchases .stat-icon {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .stat-label {
        font-size: 14px;
        color: #718096;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 4px;
    }

    .stat-description {
        font-size: 13px;
        color: #a0aec0;
    }
</style>

<div class="site-index">
    <div class="dashboard-header">
        <h1>Jovly POS – Manager Panel</h1>
        <p>Overview of your shop</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card products">
            <div class="stat-icon">📦</div>
            <div class="stat-label">Products</div>
            <div class="stat-value"><?= $totalProducts ?></div>
            <div class="stat-description">Total products in catalog</div>
        </div>

        <div class="stat-card stock">
            <div class="stat-icon">📊</div>
            <div class="stat-label">Stock</div>
            <div class="stat-value"><?= $totalStock ?></div>
            <div class="stat-description">Total units in stock</div>
        </div>

        <div class="stat-card sales">
            <div class="stat-icon">💰</div>
            <div class="stat-label">Sales</div>
            <div class="stat-value"><?= $totalSales ?></div>
            <div class="stat-description">Total sales (cheques)</div>
        </div>

        <div class="stat-card purchases">
            <div class="stat-icon">🛒</div>
            <div class="stat-label">Purchases</div>
            <div class="stat-value"><?= $totalPurchases ?></div>
            <div class="stat-description">Total purchase operations</div>
        </div>
    </div>
</div>