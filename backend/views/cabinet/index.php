<?php

use yii\helpers\Html;
use yii\grid\GridView;
use himiklab\sortablegrid\SortableGridView;
use common\models\Building;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CabinetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Кабинеты';
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
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['class' => 'width-70']
                    ],
                    [
                        'attribute' => 'number',
                        'headerOptions' => ['class' => 'width-70']
                    ],
                    [
                        'attribute' => 'building_id',
                        'format' => 'raw',
                        'value' => function($data) {
                            if($data->building) {
                                $name = $data->building->name;
                                if($clinic = $data->building->clinic) {
                                    $name .= '<br>';
                                    $name .= "({$clinic['title']})";
                                }
                                return Html::a($name, ['building/update', 'id' => $data->building->id]);
                            }
                        },
                        'filter' => Building::getList()
                    ],
                    'name',
                    'mis_id',
                    [
                        'attribute' => 'show_tickets',
                        'value' => function($data) {
                            return $data->show_tickets ? 'Да' : 'Нет';
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a('<i class="bi bi-eye"></i>', ['../room_id/'.$model->id],
                                    [
                                        'target' => '_blanc',
                                        'disabled' => true,
                                        'class' => 'cabinet-view-tooltip',
                                        'title' => $model->tooltipText()

                                    ]
                                );
                            },
                            'update' => function($url, $model) {
                                return Html::a('<i class="bi bi-pencil"></i>', ['cabinet/update', 'id' => $model->id]
                                );
                            },
                            'delete' => function($url, $model) {
                                return Html::a('<i class="bi bi-trash"></i>', ['cabinet/delete', 'id' => $model->id],
                                    [
                                        'target' => '_blanc',
                                        'class' => 'alert-modal',
                                        'data-confirm-subject' => 'кабинет "'.$model->name.'"',
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
