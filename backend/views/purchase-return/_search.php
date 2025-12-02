<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\PurchaseReturnSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="purchase-return-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'purchase_id') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'unit_cost') ?>

    <?= $form->field($model, 'total_cost') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'returned_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
