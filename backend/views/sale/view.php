<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Sale $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sale-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'total_amount:decimal',
            'total_cost:decimal',
            'note:ntext',
            [
                'attribute' => 'created_at',
                'value' => Yii::$app->formatter->asDatetime($model->created_at, 'php:M j, Y g:i:s A'),
            ],
            [
                'attribute' => 'updated_at',
                'value' => Yii::$app->formatter->asDatetime($model->updated_at, 'php:M j, Y g:i:s A'),
            ],
        ],
    ]) ?>
    <h3>Sale Items</h3>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        <?php foreach ($model->saleItems as $item): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $item->product->name ?></td>
                <td><?= $item->quantity ?></td>
                <td><?= number_format($item->unit_price, 2) ?></td>
                <td><?= number_format($item->line_total, 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


</div>
