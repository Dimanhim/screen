<?php

use yii\helpers\Html;

$this->title = 'Талоны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tickets-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-4">
            <h2>Список кабинетов</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>№ корпуса</th>
                        <th>№ кабинета</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if($cabinets) : ?>
                    <?php foreach($cabinets as $cabinet) : ?>
                        <tr class="clinic_row" data-clinic="<?= $cabinet->clinic_id ?>" data-mis_id="<?= $cabinet->number ?>">
                            <td>
                                <div class="clinic_row_clinic">
                                    <?= $cabinet->clinicName ?>
                                </div>
                            </td>
                            <td>
                                <div class="clinic_row_room">
                                    <div class="clinic_row_room_name">
                                        <?= $cabinet->mis_id ?>
                                    </div>
                                    <div class="clinic_row_room_active">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="ticket-actions">
                                    <?= Html::a('<i class="bi bi-pencil"></i>', ['#'], ['class' => 'ticket-action-update']) ?>
                                    <?= Html::a('<i class="bi bi-trash"></i>', ['#'], ['class' => 'ticket-action-update']) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr class="clinic_row">
                        <td colspan="3">
                            Кабинетов не найдено
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <h2>Список визитов</h2>
            <div id="appointment_list">
                <?= $this->render('_appointment_list', [
                    'model' => $model,
                    'list' => true,
                ]) ?>
            </div>
        </div>
    </div>
</div>
