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
    <div class="card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <p>
                <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?= SortableGridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    [
                        'attribute' => 'clinic_id',
                        'value' => function($data) {
                            return $data->clinicTitle;
                        },
                        'filter' => Yii::$app->app->getClinicList()
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="bi bi-eye"></i>', ['../building/'.$model->id], ['target' => '_blanc']);
                            },
                            'delete' => function($url, $model) {
                                return Html::a('<i class="bi bi-trash"></i>', ['building/delete', 'id' => $model->id],
                                    [
                                        'target' => '_blanc',
                                        'class' => 'alert-modal-building',
                                        'data-confirm-subject' => "{$model->name}",
                                    ]
                                );
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
