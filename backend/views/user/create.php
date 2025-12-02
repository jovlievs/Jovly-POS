<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\User;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Create User';
?>

<style>
    .page-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e2e8f0;
    }

    .page-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #1a202c;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .form-actions {
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 12px;
    }
</style>

<div class="user-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="form-card">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput([
            'maxlength' => true,
            'placeholder' => 'Enter username'
        ]) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'maxlength' => true,
            'placeholder' => 'Enter password',
            'value' => ''
        ])->label('Password') ?>

        <?= $form->field($model, 'role')->dropDownList([
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_MANAGER => 'Manager',
            User::ROLE_CASHIER => 'Cashier',
        ], ['prompt' => 'Select Role']) ?>

        <?= $form->field($model, 'status')->dropDownList([
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_INACTIVE => 'Inactive',
        ]) ?>

        <div class="form-actions">
            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>