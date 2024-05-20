<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Cabinet */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Кабинеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cabinet-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить кабинет?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'number',
            'name',
            [
                'attribute' => 'building_id',
                'format' => 'raw',
                'value' => function($data) {
                    if($data->building) {
                        return Html::a($data->building->name, ['building/view', 'id' => $data->building->id]);
                    }
                }
            ],
            'mis_id',
            [
                'attribute' => 'show_tickets',
                'value' => function($data) {
                    return $data->show_tickets ? 'Да' : 'Нет';
                }
            ],
        ],
    ]) ?>

</div>
