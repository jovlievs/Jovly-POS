<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $sku
 * @property string|null $barcode
 * @property float $cost_price
 * @property float $sale_price
 * @property int $discount
 * @property int $stock_quantity
 * @property int $is_active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category $category
 * @property Purchase[] $purchases
 * @property SaleItem[] $saleItems
 */
class Product extends ActiveRecord
{
    public static function tableName()
    {
        return 'product';
    }

    public function rules()
    {
        return [
            [['category_id', 'name'], 'required'],
            [['category_id', 'stock_quantity'], 'integer'],
            [['cost_price', 'sale_price'], 'number'],
            ['discount', 'integer', 'min' => 0, 'max' => 100],
            ['is_active', 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['sku', 'barcode'], 'string', 'max' => 64],
            [['sku'], 'unique'],
            [['barcode'], 'unique'],
            [
                ['category_id'],
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id'],
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
            'id'             => 'ID',
            'category_id'    => 'Category',
            'name'           => 'Name',
            'sku'            => 'SKU',
            'barcode'        => 'Barcode',
            'cost_price'     => 'Cost Price',
            'sale_price'     => 'Sale Price',
            'discount'       => 'Discount (%)',
            'stock_quantity' => 'Stock Quantity',
            'is_active'      => 'Active',
            'created_at'     => 'Created At',
            'updated_at'     => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->discount === null || $this->discount === '') {
            $this->discount = 0;
        }
        if ($this->discount > 100) {
            $this->discount = 100;
        }

        return true;
    }

    /**
     * Final unit price after applying product-level discount.
     */
    public function getFinalPrice(): float
    {
        $base = (float)$this->sale_price;
        $discount = (float)$this->discount;

        return round($base * (1 - $discount / 100), 2);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getPurchases()
    {
        return $this->hasMany(Purchase::class, ['product_id' => 'id']);
    }

    public function getSaleItems()
    {
        return $this->hasMany(SaleItem::class, ['product_id' => 'id']);
    }

}
