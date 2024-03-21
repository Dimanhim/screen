<?php

use yii\helpers\Html;

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
    <?php if(!$list) : ?>
        <tr>
            <td colspan="6">Выберите кабинет</td>
        </tr>
    <?php else : ?>

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
                            <?= $ticketItem['time_start'] ?>
                        </div>
                    </td>
                    <td>
                        <div class="ticket_row_name">
                            <?= $ticketItem['patient_name'] ?>
                        </div>
                    </td>
                    <td>
                        <div class="ticket_row_visit_id">
                            <?= $ticketItem['visit_id'] ?>
                        </div>
                    </td>
                    <?php if(!$ticketItem['ticket']) : ?>
                        <td>
                            <div class="ticket_row_get_ticket">
                                <a href="#">
                                    Выдать
                                </a>
                            </div>
                        </td>
                    <?php else : ?>
                        <td>
                            <div class="ticket_row_ticket">
                                <?= $ticketItem['ticket'] ?>
                            </div>
                        </td>
                    <?php endif; ?>
                    <td>
                        <div class="ticket-actions">
                            <?= Html::a('<i class="bi bi-printer"></i>', ['#'], ['class' => 'ticket-action-print']) ?>
                        </div>
                    </td>
                </tr>
        <?php $count++; endforeach; ?>

        <?php else : ?>
            <tr>
                <td colspan="6">У выбранного кабинета нет талонов</td>
            </tr>
        <?php endif; ?>
    <?php endif; ?>
    </tbody>
</table>
