<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
                    <?= $form->field($model, 'username')->textInput() ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'password')->passwordInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'password_repeat')->passwordInput() ?>
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
