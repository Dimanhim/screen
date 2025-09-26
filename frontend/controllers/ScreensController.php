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
        ]);
    }

//    public function actionTest()
//    {
//        $socket = new SocketServer(\Yii::$app->params['socket']['url'] . ':' . \Yii::$app->params['socket']['port']);
//
//        $socket->on('connection', function (ConnectionInterface $connection) {
//            $connection->write("Hello " . $connection->getRemoteAddress() . "!\n");
//            $connection->write("Welcome to this amazing server!\n");
//            $connection->write("Here's a tip: don't say anything.\n");
//
//            $connection->on('data', function ($data) use ($connection) {
//                $connection->close();
//            });
//        });
//    }
}
