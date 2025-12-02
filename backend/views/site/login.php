<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login - Jovly POS';
?>

<style>
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
    }

    .login-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        padding: 48px;
        width: 100%;
        max-width: 440px;
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-logo {
        text-align: center;
        margin-bottom: 32px;
    }

    .login-logo h1 {
        font-size: 32px;
        font-weight: 700;
        color: #1a202c;
        margin: 0 0 8px 0;
    }

    .login-logo p {
        color: #718096;
        font-size: 15px;
        margin: 0;
    }

    .login-form .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
    }

    .login-form .form-control {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .login-form .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .login-form .form-check {
        padding-left: 1.75rem;
        margin: 20px 0;
    }

    .login-form .form-check-input {
        width: 18px;
        height: 18px;
        border: 2px solid #cbd5e0;
        border-radius: 4px;
    }

    .login-form .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .login-form .form-check-label {
        color: #4a5568;
        font-size: 14px;
        margin-left: 4px;
    }

    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        margin-top: 8px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .login-footer {
        text-align: center;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
        color: #718096;
        font-size: 13px;
    }

    .help-block {
        color: #e53e3e;
        font-size: 13px;
        margin-top: 6px;
    }

    .field-loginform-username,
    .field-loginform-password {
        margin-bottom: 20px;
    }

    .invalid-feedback {
        color: #e53e3e;
        font-size: 13px;
        margin-top: 6px;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-logo">
            <h1>Jovly POS</h1>
            <h1>+998 94 703 35 12</h1>
            <p>Sign in to your account</p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'login-form'],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'form-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'username')->textInput([
            'autofocus' => true,
            'placeholder' => 'Enter your username',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'placeholder' => 'Enter your password',
            'class' => 'form-control'
        ]) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class='form-check'>{input} {label}</div>\n{error}",
            'class' => 'form-check-input'
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Sign In', [
                'class' => 'btn btn-login',
                'name' => 'login-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="login-footer">
            © <?= date('Y') ?> Jovly POS. All rights reserved.
        </div>
    </div>
</div>