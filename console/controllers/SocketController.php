<?php

namespace console\controllers;

use common\components\SocketHandler;
use yii\console\Controller;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class SocketController extends Controller
{
    public function actionRun()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    \Yii::$app->socket
                )
            ),
            \Yii::$app->params['socket']['port'], \Yii::$app->params['socket']['host']
        );

        $server->run();
    }
}
