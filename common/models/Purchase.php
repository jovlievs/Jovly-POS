<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "purchase".
 *
 * @property int $id
 * @property int $product_id
 * @property int|null $supplier_id
 * @property int $quantity
 * @property float $unit_cost
 * @property float $total_cost
 * @property string|null $note
 * @property string|null $purchased_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Product $product
 * @property Supplier|null $supplier
 * @property PurchaseReturn[] $purchaseReturns
 */
class Purchase extends ActiveRecord
{
    public static function tableName()
    {
        return 'purchase';
    }

    public function rules()
    {
        return [
            [['product_id', 'quantity', 'unit_cost'], 'required'],
            [['product_id', 'supplier_id', 'quantity'], 'number'],
            [['unit_cost', 'total_cost'], 'number'],
            [['note'], 'string'],
            [['purchased_at'], 'safe'],
            [
                ['product_id'],
                'exist',
                'targetClass' => Product::class,
                'targetAttribute' => ['product_id' => 'id'],
            ],
            [
                ['supplier_id'],
                'exist',
                'targetClass' => Supplier::class,
                'targetAttribute' => ['supplier_id' => 'id'],
                'skipOnEmpty' => true,
            ],
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

    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'product_id'   => 'Product',
            'supplier_id'  => 'Supplier',
            'quantity'     => 'Quantity',
            'unit_cost'    => 'Unit Cost',
            'total_cost'   => 'Total Cost',
            'note'         => 'Note',
            'purchased_at' => 'Purchased At',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
        ];
    }

    public function beforeValidate()
    {
        if (!parent::beforeValidate()) {
            return false;
        }

        if (empty($this->purchased_at)) {
            $this->purchased_at = date('Y-m-d H:i:s');
        }
        if ($this->total_cost === null) {
            $this->total_cost = $this->unit_cost * $this->quantity;
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $product = $this->product;
            if ($product) {
                $product->stock_quantity += $this->quantity;
                // last purchase price wins
                $product->cost_price = $this->unit_cost;
                $product->save(false);
            }
        }
        // no automatic handling of updates/deletes; use purchase_return instead
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplier_id']);
    }

    public function getPurchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class, ['purchase_id' => 'id']);
    }
}
