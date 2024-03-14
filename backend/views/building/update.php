<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Cabinet */

$this->title = 'Редактирование корпуса: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Корпуса', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="cabinet-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
