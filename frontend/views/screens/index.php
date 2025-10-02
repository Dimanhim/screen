<?php
    $this->title = Yii::$app->settings->getParam('app_name');
    $showed = [
        'main-header',
        'no-schedule-screen',
        'room-info',
        'invite-screen',
        'main-footer'
    ];
?>
<div data-template x-data="screens" x-init="initDefault">
    <div class="wrapper">

        <?php foreach($showed as $viewName) : ?>
            <?= $this->render('chunks/' . $viewName, [
                'room' => $roomId,
                'mode' => $mode,
            ]) ?>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->render('_alpine', [
    'roomId' => $roomId,
    'mode' => $mode,
    'roomNumber' => $roomNumber,
]) ?>
