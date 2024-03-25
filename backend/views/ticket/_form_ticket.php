<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use backend\models\FormTicket;
use kartik\widgets\DatePicker;

$model = new FormTicket()

?>
<div class="modal fade screens-modal" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['id' => 'form-ticket']) ?>
                <h4>Добавление визита <span id="room-name"></span></h4>
                <?= $form->field($model, 'first_name')->textInput(['placeholder' => $model->attributeLabels()['first_name']]) ?>
                <?= $form->field($model, 'last_name')->textInput(['placeholder' => $model->attributeLabels()['last_name']]) ?>
                <?= $form->field($model, 'third_name')->textInput(['placeholder' => $model->attributeLabels()['third_name']]) ?>
                <?= $form->field($model, 'birth_date')->textInput(['placeholder' => $model->attributeLabels()['birth_date'], 'class' => 'form-control date-picker']) ?>
                <?= $form->field($model, 'mobile')->textInput(['placeholder' => $model->attributeLabels()['mobile'], 'class' => 'form-control phone-mask']) ?>

                <?= $form->field($model, 'time_start', ['template' => "{input}"])->hiddenInput(['value' => '']) ?>
                <?= $form->field($model, 'time_end', ['template' => "{input}"])->hiddenInput(['value' => '']) ?>
                <?= $form->field($model, 'clinic_id', ['template' => "{input}"])->hiddenInput(['value' => '']) ?>
                <?= $form->field($model, 'doctor_id', ['template' => "{input}"])->hiddenInput(['value' => '']) ?>
                <?= $form->field($model, 'room', ['template' => "{input}"])->hiddenInput(['value' => '']) ?>

                <div class="row">
                    <div class="col-md-3">
                        <?= Html::submitButton('Записать', ['class' => "btn btn-success"]) ?>
                    </div>
                    <div class="col-md-9">
                        <p class="info-message" style="margin-top: 7px;"></p>
                    </div>
                </div>

                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
