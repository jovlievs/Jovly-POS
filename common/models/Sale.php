<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Transaction;

/**
 * This is the model class for table "sale".
 *
 * @property int $id
 * @property float $total_amount
 * @property float $total_cost
 * @property string|null $note
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SaleItem[] $saleItems
 */
class Sale extends ActiveRecord
{
    public static function tableName()
    {
        return 'sale';
    }

    public function rules()
    {
        return [
            [['total_amount', 'total_cost'], 'number'],
            [['note'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'total_amount' => 'Total Amount',
            'total_cost'   => 'Total Cost',
            'note'         => 'Note',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * Create a sale from a simple cart array.
     *
     * $items = [
     *   ['product_id' => 1, 'quantity' => 2],
     *   ['product_id' => 5, 'quantity' => 1],
     * ];
     */
    public static function createFromCart(array $items, ?string $note = null): ?self
    {
        if (empty($items)) {
            return null;
        }

        $db = static::getDb();
        $transaction = $db->beginTransaction(Transaction::SERIALIZABLE);

        try {
            $sale = new self();
            $sale->total_amount = 0;
            $sale->total_cost = 0;
            $sale->note = $note;

            if (!$sale->save(false)) {
                $transaction->rollBack();
                return null;
            }

            $totalAmount = 0.0;
            $totalCost = 0.0;

            foreach ($items as $row) {
                $productId = (int)($row['product_id'] ?? 0);
                $qty = (float)($row['quantity'] ?? 0);

                if ($productId <= 0 || $qty <= 0) {
                    continue;
                }

                $product = Product::findOne($productId);
                if (!$product) {
                    throw new \RuntimeException('Product not found: ' . $productId);
                }

                $unitPrice = $product->getFinalPrice();
                $unitCost = (float)$product->cost_price;
                $lineTotal = $unitPrice * $qty;

                $saleItem = new SaleItem();
                $saleItem->sale_id = $sale->id;
                $saleItem->product_id = $product->id;
                $saleItem->quantity = $qty;
                $saleItem->unit_price = $unitPrice;
                $saleItem->unit_cost = $unitCost;
                $saleItem->line_total = $lineTotal;

                if (!$saleItem->save()) {
                    $transaction->rollBack();
                    return null;
                }

                // Update stock (negative allowed)
                $product->stock_quantity -= $qty;
                $product->save(false);

                $totalAmount += $lineTotal;
                $totalCost += $unitCost * $qty;
            }

            $sale->total_amount = $totalAmount;
            $sale->total_cost = $totalCost;
            $sale->save(false, ['total_amount', 'total_cost']);

            $transaction->commit();
            return $sale;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function getSaleItems()
    {
        return $this->hasMany(SaleItem::class, ['sale_id' => 'id']);
    }

//    public function getProduct()
//    {
//        return $this->hasOne(Product::class, ['id' => 'product_id']);
//    }

}
