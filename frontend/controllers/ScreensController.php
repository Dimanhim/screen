<?php

namespace frontend\controllers;

use common\models\Cabinet;
use yii\web\Controller;
use React\Socket\SocketServer;
use React\Socket\ConnectionInterface;

class ScreensController extends Controller
{
    public $layout = 'front';

    public function actionIndex($room = null, $mode = null)
    {
        $room = Cabinet::getByUniqueId($room);

        return $this->render('index', [
            'roomId' => $room->unique_id ?? null,
            'mode' => $mode ?? null,
            'roomNumber' => $room->number ?? null,
        ]);
    }
}
