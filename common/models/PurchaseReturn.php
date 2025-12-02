<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "purchase_return".
 *
 * @property int $id
 * @property int $purchase_id
 * @property float $quantity
 * @property float $unit_cost
 * @property float $total_cost
 * @property string|null $reason
 * @property string|null $returned_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Purchase $purchase
 */
class PurchaseReturn extends ActiveRecord
{
    public static function tableName()
    {
        return 'purchase_return';
    }

    public function rules()
    {
        return [
            [['purchase_id', 'quantity', 'unit_cost'], 'required'],
            [['purchase_id', 'quantity'], 'number'],
            [['unit_cost', 'total_cost'], 'number'],
            [['reason'], 'string'],
            [['returned_at'], 'safe'],
            [
                ['purchase_id'],
                'exist',
                'targetClass' => Purchase::class,
                'targetAttribute' => ['purchase_id' => 'id'],
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
            'id'          => 'ID',
            'purchase_id' => 'Purchase',
            'quantity'    => 'Quantity',
            'unit_cost'   => 'Unit Cost',
            'total_cost'  => 'Total Cost',
            'reason'      => 'Reason',
            'returned_at' => 'Returned At',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        // Set returned_at if empty
        if (empty($this->returned_at)) {
            $this->returned_at = date('Y-m-d H:i:s');
        }

        // Cast to numeric types to avoid TypeError
        $qty  = (float)$this->quantity;
        $unit = (float)$this->unit_cost;

        // Always compute total_cost from qty * unit_cost
        $this->total_cost = $qty * $unit;

        return true;
    }




    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $purchase = $this->purchase;
            if ($purchase && $purchase->product) {
                $product = $purchase->product;
                $product->stock_quantity -= $this->quantity; // negative allowed
                $product->save(false);
            }
        }
    }

    public function getPurchase()
    {
        return $this->hasOne(Purchase::class, ['id' => 'purchase_id']);
    }
}
