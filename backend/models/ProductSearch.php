<?php

namespace backend\models;

use common\models\Product;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    // Public property for filtering by category name
    public $categoryName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'is_active'], 'integer'],
            [['name', 'sku', 'barcode', 'categoryName'], 'safe'],
            [['stock_quantity', 'cost_price', 'sale_price', 'discount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find()
            ->joinWith(['category']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id',
                    'name',
                    'sku',
                    'barcode',
                    'stock_quantity',
                    'cost_price',
                    'sale_price',
                    'discount',
                    'is_active',
                    'created_at',
                    'categoryName' => [
                        'asc' => ['category.name' => SORT_ASC],
                        'desc' => ['category.name' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Grid filtering conditions
        $query->andFilterWhere([
            'product.id' => $this->id,
            'product.category_id' => $this->category_id,
            'product.stock_quantity' => $this->stock_quantity,
            'product.cost_price' => $this->cost_price,
            'product.sale_price' => $this->sale_price,
            'product.discount' => $this->discount,
            'product.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'product.name', $this->name])
            ->andFilterWhere(['like', 'product.sku', $this->sku])
            ->andFilterWhere(['like', 'product.barcode', $this->barcode])
            ->andFilterWhere(['like', 'category.name', $this->categoryName]);

        return $dataProvider;
    }
}