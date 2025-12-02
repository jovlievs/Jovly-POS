<?php

namespace backend\models;

use common\models\Purchase;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class PurchaseSearch extends Purchase
{
    // Add public properties for related fields
    public $productName;
    public $supplierName;

    public function rules()
    {
        return [
            [['id', 'product_id', 'supplier_id', 'quantity'], 'integer'],
            [['unit_cost', 'total_cost'], 'number'],
            [['purchased_at'], 'safe'],
            // Add these for filtering by name
            [['productName', 'supplierName'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Purchase::find()
            ->joinWith(['product', 'supplier']);  // Join the related tables

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
        ]);

        // Enable sorting by related fields
        $dataProvider->sort->attributes['productName'] = [
            'asc' => ['product.name' => SORT_ASC],
            'desc' => ['product.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplierName'] = [
            'asc' => ['supplier.name' => SORT_ASC],
            'desc' => ['supplier.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Filter by purchase fields
        $query->andFilterWhere([
            'purchase.id' => $this->id,
            'purchase.product_id' => $this->product_id,
            'purchase.supplier_id' => $this->supplier_id,
            'purchase.quantity' => $this->quantity,
        ]);

        // ILIKE-style filtering (case-insensitive) for Product name
        $query->andFilterWhere(['like', 'product.name', $this->productName]);

        // ILIKE-style filtering (case-insensitive) for Supplier name
        $query->andFilterWhere(['like', 'supplier.name', $this->supplierName]);

        return $dataProvider;
    }
}