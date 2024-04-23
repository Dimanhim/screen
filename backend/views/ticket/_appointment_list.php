<?php

use yii\helpers\Html;
use common\components\Helpers;
use common\models\Ticket;
use yii\helpers\Url;

?>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>№</th>
        <th>Время приема</th>
        <th>Имя</th>
        <th>ID визита</th>
        <th>Талон</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if($list) : ?>
        <?php if($ticketList = $model->ticketList()) : ?>
            <?php $count = 1; foreach($ticketList as $ticketItem) : ?>
                <tr class="ticket_row">
                    <td>
                        <div class="ticket_row_number">
                            <?= $count ?>
                        </div>
                    </td>
                    <td>
                        <div class="ticket_row_time_start">
                            <?= Helpers::getTimeFromDatetime($ticketItem['time_start']) ?>
                        </div>
                    </td>
                    <td>
                        <div class="ticket_row_name">
                            <?= $ticketItem['patient_name'] ?>
                        </div>
                    </td>
                    <td>
                        <div class="ticket_row_visit_id">
                            <?= $ticketItem['id'] ?>
                        </div>
                    </td>
                    <?php
                        $ticketName = Ticket::ticketName($ticketItem);
                    ?>
                    <?php if(!$ticketName['print']) : ?>
                        <td>
                            <div class="ticket_row_get_ticket">
                                <a
                                    href="<?= Url::to([
                                        'ticket/generate-ticket',
                                        'clinic_id' => $ticketItem['clinic_id'],
                                        'room' => $ticketItem['room'],
                                        'time_start' =>  $ticketItem['time_start'],
                                        'time_end' =>  $ticketItem['time_end'],
                                        'patient_name' =>  $ticketItem['patient_name'],
                                        'mobile' =>  $ticketItem['patient_phone'],
                                        'appointment_id' =>  $ticketItem['id'],
                                    ]) ?>"
                                    class="get_ticket_js"
                                >
                                    Выдать
                                </a>
                            </div>
                        </td>
                    <?php else : ?>
                        <td>
                            <div class="ticket_row_ticket">
                                <?= $ticketName['name'] ?>
                            </div>
                        </td>
                    <?php endif; ?>
                    <td>
                        <div class="ticket-actions">
                            <?php if($ticketName['print'] and $model->cabinet) {
                                echo Html::a('<i class="bi bi-printer"></i>', ['#'], ['class' => 'ticket-action-print', 'data-room' => $ticketItem['room'] ? 'Кабинет № ' . $model->cabinet->id : '', 'data-ticket' => $ticketName['name']]);
                            } ?>
                        </div>
                    </td>
                </tr>
                <?php $count++; endforeach; ?>

        <?php else : ?>
            <tr>
                <td colspan="6">У выбранного кабинета нет талонов</td>
            </tr>
        <?php endif; ?>

    <?php else : ?>
        <tr>
            <td colspan="6">Выберите кабинет</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>


