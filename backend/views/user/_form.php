<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$btnClass = 'active';
$blockClass = null;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Основная информация
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'name')->textInput() ?>
                    <?= $form->field($model, 'username')->textInput(['autocomplete' => 'new-password']) ?>
                    <?php
                        if($model->isNewRecord or $model->hasErrors()) {
                            $btnClass = null;
                            $blockClass = 'active';
                        }
                    ?>
                    <div class="password-message <?= $btnClass ?>">
                        <button class="btn btn-outline-primary btn-password-change">Установить новый пароль</button>
                    </div>
                    <div class="password-block <?= $blockClass ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'new-password']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'password_repeat')->passwordInput(['autocomplete' => 'new-password']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <?= $this->render('_accesses', [
                'form' => $form,
                'model' => $model,
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
