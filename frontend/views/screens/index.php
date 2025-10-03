<?php
$this->registerJsFile('/js/QRCreator.js', ['position' => \yii\web\View::POS_HEAD]);

$this->title = Yii::$app->settings->getParam('app_name');

$showed = [
    'main-footer',
    'room-info',
];
?>
<div data-template x-data="screens" x-init="initDefault">
    <div class="screen">

        <?php foreach($showed as $viewName) : ?>
            <?= $this->render('chunks/' . $viewName, [
                'room' => $roomId,
                'mode' => $mode,
            ]) ?>
        <?php endforeach; ?>
    </div>
    <div class="sprite" aria-hidden="true">
        <svg display="none" xmlns="http://www.w3.org/2000/svg">
            <symbol id="plus" viewBox="0 0 108 108">
                <g clip-path="url(#clip0_101_990)">
                    <path d="M54 0V108M108 54L0 54" stroke="#2D282A" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <defs>
                    <clipPath id="clip0_101_990">
                        <rect width="108" height="108" fill="white" />
                    </clipPath>
                </defs>
            </symbol>
            <symbol id="close" viewBox="0 0 108 108">
                <path d="M100 8L8 100M8 8L100 100" stroke="#2D282A" stroke-width="8" stroke-linecap="square" stroke-linejoin="round" />
            </symbol>
        </svg>
    </div>
</div>




<?= $this->render('_alpine', [
    'roomId' => $roomId,
    'mode' => $mode,
    'roomNumber' => $roomNumber,
]) ?>
