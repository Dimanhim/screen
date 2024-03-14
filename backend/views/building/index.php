<?php

use yii\helpers\Html;
use yii\grid\GridView;
use himiklab\sortablegrid\SortableGridView;
use common\models\Building;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CabinetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Корпуса';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">

    <div class="row">
        <div class="col-6">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="col-6">
            <div class="btn-container">
                <?= Html::a('<i class="bi bi-plus"></i> Добавить', ['create'], ['class' => 'btn btn-primary float-right']) ?>
            </div>
        </div>
    </div>

    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'view' => function($url, $model) {
                        return Html::a(Building::getViewSvg(), ['../building/'.$model->id], ['target' => '_blanc']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
