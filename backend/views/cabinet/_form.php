<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Cabinet;
use common\models\Building;

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
                    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($model, 'building_id')->dropDownList(Building::getList(), ['prompt' => '[Не выбрано]']) ?>
                    <?= $form->field($model, 'mis_id')->textInput() ?>
                    <?= $form->field($model, 'show_tickets')->checkbox(['label' => Html::tag('span','Выводить в разделе талонов'), 'labelOptions' => ['class' => 'ui-checkbox']]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
