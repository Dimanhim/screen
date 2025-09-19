<?php
    $showed = [
        'main-header',
        'no-schedule-screen',
        'room-info',
        'invite-screen',
        'main-footer'
    ];
?>
<div data-template x-data="screens" x-init="setDefault">
    <div class="wrapper">

        <?php foreach($showed as $viewName) : ?>
            <?= $this->render('chunks/' . $viewName, [
                'room' => $roomId,
                'roomNumber' => $roomNumber,
                'mode' => $mode,
            ]) ?>
        <?php endforeach; ?>
    </div>
</div>

<!--<?//= $this->render('regular', []) ?>-->
<!--<?//= $this->render('tickets', []) ?>-->
<?= $this->render('_alpine', [
    'roomId' => $roomId,
    'roomNumber' => $roomNumber,
    'mode' => $mode,
]) ?>
