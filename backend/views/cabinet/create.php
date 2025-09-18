<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Cabinet */

$this->title = 'Добавление кабинета';
$this->params['breadcrumbs'][] = ['label' => 'Кабинеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-create">
    <div class="card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
