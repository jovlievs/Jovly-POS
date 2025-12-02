<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PurchaseReturn $model */

$this->title = 'Update Purchase Return: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Returns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-return-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
