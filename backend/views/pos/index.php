<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Product[] $products */
/** @var int $activeBasket */
/** @var array $currentItems */

$this->title = 'POS - New Sale';

// Prepare product data for JS
$productData = [];
foreach ($products as $product) {
    $productData[] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'barcode' => $product['barcode'] ?? '',
        'stock' => $product['stock_quantity'],
        'price' => $product['sale_price'] ?? 0,
    ];
}
$productDataJson = Json::encode($productData);
$currentItemsJson = Json::encode($currentItems);

?>

    <style>
        .pos-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .pos-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e2e8f0;
        }

        .pos-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
        }

        .basket-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
        }

        .basket-tab {
            padding: 10px 20px;
            border: 2px solid #e2e8f0;
            background: white;
            border-radius: 8px;
            font-weight: 600;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .basket-tab:hover {
            border-color: #667eea;
            color: #667eea;
            text-decoration: none;
        }

        .basket-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .search-section {
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .search-input-wrapper {
            position: relative;
            margin-bottom: 12px;
        }

        .search-input {
            width: 100%;
            padding: 14px 48px 14px 16px;
            font-size: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #a0aec0;
        }

        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #667eea;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: none;
        }

        .autocomplete-results.show {
            display: block;
        }

        .autocomplete-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f7fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s ease;
        }

        .autocomplete-item:hover,
        .autocomplete-item.selected {
            background: #f7fafc;
        }

        .autocomplete-item-name {
            font-weight: 600;
            color: #2d3748;
        }

        .autocomplete-item-stock {
            font-size: 13px;
            color: #718096;
        }

        .autocomplete-item-stock.low {
            color: #e53e3e;
            font-weight: 600;
        }

        .search-hint {
            font-size: 13px;
            color: #718096;
            margin: 0;
        }

        .items-table {
            width: 100%;
            margin-bottom: 24px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .items-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f7fafc;
        }

        .items-table tbody tr:hover {
            background: #f7fafc;
        }

        .items-table input[type="number"] {
            width: 100px;
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
        }

        .items-table input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-remove {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .btn-remove:hover {
            transform: translateY(-2px);
        }

        .add-product-btn {
            color: #667eea;
            font-weight: 600;
            cursor: pointer;
            padding: 10px;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            text-align: center;
            transition: all 0.2s ease;
            margin-bottom: 24px;
        }

        .add-product-btn:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .note-section {
            margin-bottom: 24px;
        }

        .note-section label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            display: block;
        }

        .note-section textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
        }

        .note-section textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .action-buttons {
            display: flex;
            gap: 12px;
        }

        .btn-save {
            background: white;
            border: 2px solid #e2e8f0;
            color: #4a5568;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-save:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .btn-complete {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            color: white;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .btn-complete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 233, 123, 0.4);
        }

        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: #a0aec0;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }
    </style>

    <div class="pos-container">
        <div class="pos-header">
            <h1>🛒 <?= Html::encode($this->title) ?></h1>
        </div>

        <!-- Basket Tabs -->
        <div class="basket-tabs">
            <?php for ($b = 1; $b <= 3; $b++): ?>
                <?php $isActive = $b === $activeBasket; ?>
                <?= Html::a(
                    'Basket ' . $b,
                    ['index', 'basket' => $b],
                    ['class' => 'basket-tab' . ($isActive ? ' active' : '')]
                ) ?>
            <?php endfor; ?>
        </div>

        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <div class="alert alert-<?= $type === 'error' ? 'danger' : 'success' ?>" style="margin-bottom: 20px;">
                <?= Html::encode($message) ?>
            </div>
        <?php endforeach; ?>

        <?php $form = ActiveForm::begin(['id' => 'pos-form']); ?>

        <?= Html::hiddenInput('activeBasket', $activeBasket) ?>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-input-wrapper">
                <input
                        type="text"
                        id="product-search"
                        class="search-input"
                        placeholder="Type product name or scan barcode..."
                        autocomplete="off"
                >
                <span class="search-icon">🔍</span>
                <div id="autocomplete-results" class="autocomplete-results"></div>
            </div>
            <p class="search-hint">💡 Tip: Use barcode scanner or type product name. Press Enter to add.</p>
        </div>

        <!-- Items Table -->
        <table class="items-table" id="pos-items-table">
            <thead>
            <tr>
                <th style="width: 40px;">#</th>
                <th>Product</th>
                <th style="width: 120px;">Quantity</th>
                <th style="width: 60px;"></th>
            </tr>
            </thead>
            <tbody id="items-tbody">
            <!-- Rows will be generated by JS -->
            </tbody>
        </table>

        <div id="empty-state" class="empty-state">
            <div class="empty-state-icon">🛍️</div>
            <p>No items in basket. Search and add products above.</p>
        </div>

        <!-- Note Section -->
        <div class="note-section">
            <label>Payment Note</label>
            <?= Html::textarea('note', '', [
                'class' => 'form-control',
                'rows' => 2,
                'placeholder' => 'Empty = card; or write "cash", "cash+card 50/30", etc.',
            ]) ?>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <?= Html::submitButton('Save Basket', [
                'class' => 'btn-save',
                'name'  => 'submitType',
                'value' => 'save',
            ]) ?>
            <?= Html::submitButton('✓ Complete Sale', [
                'class' => 'btn-complete',
                'name'  => 'submitType',
                'value' => 'sale',
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<<JS
(function() {
    const products = $productDataJson || [];
    const currentItems = $currentItemsJson || [];
    const tbody = document.getElementById('items-tbody');
    const emptyState = document.getElementById('empty-state');
    const searchInput = document.getElementById('product-search');
    const resultsDiv = document.getElementById('autocomplete-results');
    
    let items = [];
    let selectedIndex = -1;
    let filteredProducts = [];

    // Initialize with current items
    function init() {
        if (currentItems && currentItems.length > 0) {
            items = currentItems.map(item => ({
                product_id: item.product_id,
                quantity: item.quantity || 1,
                productData: products.find(p => p.id == item.product_id)
            }));
        }
        renderItems();
    }

    // Search products
    function searchProducts(query) {
        query = query.toLowerCase().trim();
        if (!query) {
            return [];
        }
        
        return products.filter(p => {
            const nameMatch = p.name.toLowerCase().includes(query);
            const barcodeMatch = p.barcode && p.barcode.toLowerCase().includes(query);
            return nameMatch || barcodeMatch;
        }).slice(0, 10); // Limit to 10 results
    }

    // Render autocomplete results
    function renderAutocomplete(results) {
        if (!results || results.length === 0) {
            resultsDiv.classList.remove('show');
            return;
        }

        let html = '';
        results.forEach((product, index) => {
            const stockClass = product.stock < 10 ? 'low' : '';
            html += `
                <div class="autocomplete-item" data-index="\${index}" data-id="\${product.id}">
                    <span class="autocomplete-item-name">\${product.name}</span>
                    <span class="autocomplete-item-stock \${stockClass}">Stock: \${product.stock}</span>
                </div>
            `;
        });
        
        resultsDiv.innerHTML = html;
        resultsDiv.classList.add('show');
        selectedIndex = -1;
    }

    // Add product to basket
    function addProduct(productId) {
        const product = products.find(p => p.id == productId);
        if (!product) return;

        // Check if already in basket
        const existing = items.find(item => item.product_id == productId);
        if (existing) {
            existing.quantity = parseFloat(existing.quantity) + 1;
        } else {
            items.push({
                product_id: productId,
                quantity: 1,
                productData: product
            });
        }

        renderItems();
        searchInput.value = '';
        resultsDiv.classList.remove('show');
        searchInput.focus();
    }

    // Remove product
    function removeProduct(index) {
        items.splice(index, 1);
        renderItems();
    }

    // Update quantity
    function updateQuantity(index, value) {
        const qty = parseFloat(value);
        if (qty > 0) {
            items[index].quantity = qty;
        }
    }

    // Render items table
    function renderItems() {
        if (items.length === 0) {
            tbody.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';
        
        let html = '';
        items.forEach((item, index) => {
            const product = item.productData;
            html += `
                <tr>
                    <td>\${index + 1}</td>
                    <td>
                        \${product.name}
                        <input type="hidden" name="items[\${index}][product_id]" value="\${item.product_id}">
                    </td>
                    <td>
                        <input 
                            type="number" 
                            name="items[\${index}][quantity]" 
                            value="\${item.quantity}"
                            min="0.01"
                            step="0.01"
                            data-index="\${index}"
                            class="qty-input"
                        >
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn-remove" data-index="\${index}">×</button>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;

        // Attach event listeners
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                removeProduct(parseInt(this.dataset.index));
            });
        });

        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function() {
                updateQuantity(parseInt(this.dataset.index), this.value);
            });
        });
    }

    // Search input events
    searchInput.addEventListener('input', function() {
        const query = this.value;
        filteredProducts = searchProducts(query);
        renderAutocomplete(filteredProducts);
    });

    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = resultsDiv.querySelectorAll('.autocomplete-item');
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
            updateSelection(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            selectedIndex = Math.max(selectedIndex - 1, -1);
            updateSelection(items);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && items[selectedIndex]) {
                const productId = items[selectedIndex].dataset.id;
                addProduct(productId);
            } else if (filteredProducts.length === 1) {
                // Auto-select if only one result
                addProduct(filteredProducts[0].id);
            }
        } else if (e.key === 'Escape') {
            resultsDiv.classList.remove('show');
            selectedIndex = -1;
        }
    });

    function updateSelection(items) {
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('selected');
                item.scrollIntoView({ block: 'nearest' });
            } else {
                item.classList.remove('selected');
            }
        });
    }

    // Click on autocomplete item
    resultsDiv.addEventListener('click', function(e) {
        const item = e.target.closest('.autocomplete-item');
        if (item) {
            const productId = item.dataset.id;
            addProduct(productId);
        }
    });

    // Close autocomplete when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.classList.remove('show');
        }
    });

    // Initialize
    init();
    searchInput.focus();
})();
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>