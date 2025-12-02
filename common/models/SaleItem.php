<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sale_item".
 *
 * @property int $id
 * @property int $sale_id
 * @property int $product_id
 * @property float $quantity
 * @property float $unit_price
 * @property float $unit_cost
 * @property float $line_total
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Sale $sale
 * @property Product $product
 */
class SaleItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'sale_item';
    }

    public function rules()
    {
        return [
            [['sale_id', 'product_id', 'quantity', 'unit_price', 'unit_cost', 'line_total'], 'required'],
            [['sale_id', 'product_id', 'quantity'], 'number'],
            [['unit_price', 'unit_cost', 'line_total'], 'number'],
            [
                ['sale_id'],
                'exist',
                'targetClass' => Sale::class,
                'targetAttribute' => ['sale_id' => 'id'],
            ],
            [
                ['product_id'],
                'exist',
                'targetClass' => Product::class,
                'targetAttribute' => ['product_id' => 'id'],
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
            'id'         => 'ID',
            'sale_id'    => 'Sale',
            'product_id' => 'Product',
            'quantity'   => 'Quantity',
            'unit_price' => 'Unit Price',
            'unit_cost'  => 'Unit Cost',
            'line_total' => 'Line Total',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSale()
    {
        return $this->hasOne(Sale::class, ['id' => 'sale_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }


}
