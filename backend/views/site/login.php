<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\widgets\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Html as Helper;

$this->title = 'Вход в систему';
?>
<div class="site-login">
    <div class="mt-5 offset-lg-3 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="card-body">
                <div class="card-header">
                    <p style="margin: 0;">Пожалуйста, заполните поля ниже</p>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <?= $form->field($model, 'rememberMe')->checkbox(['label' => Helper::tag('span','Запомнить меня'), 'labelOptions' => ['class' => 'ui-checkbox']]) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Вход', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
