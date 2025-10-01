<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\bootstrap5\ActiveForm;
//use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Url;

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="settings-index">
    <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <?= Html::encode($this->title) ?>
                    </div>
                    <div class="card-body">
                        <?= $form->field($model, 'app_name')->textInput()->label('Название приложения') ?>
                        <?= $form->field($model, 'rnova_api_url')->textInput()->label('Адрес API МИС') ?>
                        <?= $form->field($model, 'rnova_api_key')->textInput()->label('Ключ API МИС') ?>
                        <?= $form->field($model, 'rnova_webhook_key')->textInput()->label('Ключ вебхука из МИС') ?>
                        <?= $form->field($model, 'socket_host')->textInput()->label('Хост сокета') ?>
                        <?= $form->field($model, 'socket_port')->textInput()->label('Порт сокета') ?>
                        <?= $form->field($model, 'socket_url')->textInput()->label('URL сокета') ?>
                        <div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
