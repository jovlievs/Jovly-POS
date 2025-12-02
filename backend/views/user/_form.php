<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->dropDownList([
        User::ROLE_ADMIN   => 'Admin',
        User::ROLE_MANAGER => 'Manager',
        User::ROLE_CASHIER => 'Cashier',
    ], ['prompt' => 'Select role']) ?>

    <?= $form->field($model, 'status')->dropDownList([
        User::STATUS_ACTIVE   => 'Active',
        User::STATUS_INACTIVE => 'Inactive',
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput()->label(
        $model->isNewRecord ? 'Password' : 'New Password (leave empty to keep current)'
    ) ?>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Create' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
