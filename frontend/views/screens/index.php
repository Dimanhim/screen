<?php
$this->registerJsFile('/screens/js/client.js?v='.mt_rand(1000,10000), ['position' => \yii\web\View::POS_HEAD]);
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
                'mode' => $mode,
            ]) ?>
        <?php endforeach; ?>
    </div>
</div>

<!--<?//= $this->render('regular', []) ?>-->
<!--<?//= $this->render('tickets', []) ?>-->
<?= $this->render('_alpine', [
    'roomId' => $roomId,
    'mode' => $mode,
]) ?>
