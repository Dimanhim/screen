<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Cabinet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cabinet-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'clinic_id')->dropDownList(Yii::$app->app->getClinicList(), ['prompt' => '[Не выбрано]']) ?>
                    <?= $form->field($model, 'is_active')->checkbox(['label' => Html::tag('span','Активность'), 'labelOptions' => ['class' => 'ui-checkbox']]) ?>

                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
