<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'username',
            'password',
            [
                'attribute' => 'sections_accesses',
                'format' => 'raw',
                'value' => function($data) {
                    return $data->generalAccessesHtml;
                }
            ],
            //'password_hash',
            //'password_reset_token',
            //'email:email',
            //'status',
            //'is_admin',
            //'is_active',
            //'deleted',
            //'position',
            //'created_at',
            //'updated_at',
            //'verification_token',
            [
                'class' => ActionColumn::className(),
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
