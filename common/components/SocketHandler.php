<?php

namespace common\components;

use console\controllers\SocketController;
use Yii;
use common\models\Cabinet;
use Exception;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use yii\base\Component;
use WebSocket\Client;

class SocketHandler extends Component implements MessageComponentInterface
{
    public static $clients = [];
    /*public $map = [
        [
            'roomId' => null,
            'resourceId' => null,
            'client' => null,
        ],
        ...
    ];*/

    public $map = [];

    public function init()
    {
        return parent::init();
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo 'message from - ' . $from->resourceId . ' ';
        echo 'message - ' . $msg . ' ';
        $this->handleMessage($msg, $from);
        $from->send('some data');
        /*if($this->getMethod($msg) == 'register') {
            $this->setToMap($from, $msg);
        }*/

//        foreach($this->clients as $client) {
//            $client->send('some client data !');
//        }
    }

    public function onClose(ConnectionInterface $conn) {
        echo "close";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo $e->getMessage();
    }

    private function handleMessage($message, $client)
    {

        $data = @json_decode($message, true);
        \Yii::$app->infoLog->add('handleMessage data', $data);
        if (!$data || !isset($data['method'])) {
            return false;
        }

        switch ($data['method']) {
            case 'register': {
                \Yii::$app->infoLog->add('register', $data);
                $this->registerUser($data, $client);
                $client->send('client registered');
            }
                break;
            case 'update_appointment' : {
                \Yii::$app->infoLog->add('update_appointment', $data);
                $this->updateScreen($data);
            }
                break;
            case 'notification':
                //$this->sendNotification($data);
                break;
        }
        return true;
    }



    private function registerUser($data, $client)
    {
        $cache = $data['cache'];
        $roomId = $data['roomId'];
        $client = $client;

        self::$clients[$roomId] = $client;

    }

    public function updateScreen($data)
    {
        // выбираем клиента из карты и отправляем ему json
        $client = self::$clients['68cbb274376ff'];
        $client->send('some json');
    }

    public static function sendMessage($message)
    {
        if (!is_string($message)) {
            $message = json_encode($message);
        }
        try {
            $client = new Client(
                "wss://docscreen.rnova.org/ws/"
                //Yii::$app->params['socket']['url'] . ':' . Yii::app()->params['socket']['port']
            );
            $client->send($message);

        } catch (Exception $e) {
            return false;
        }
        return true;
    }

//    public function sendByRoomId($roomName, $message)
//    {
//        $json = json_encode($message);
//        $room = Cabinet::findOne(['mis_id' => $roomName]);
//        // здесь проверить клинику
//
//        foreach($this->clients as $client) {
//            $client->send($json);
//        }
//
//
//    }

//    public function setToMap($client, $json)
//    {
//        if($message = @json_decode($json, true)) {
//            $roomId = $message['roomId'];
//            echo 'roomId - '.$roomId . ' ';
//            echo 'resourceId - '.$client->resourceId . ' ';
//            if(!$roomId || !$client->resourceId) return false;
//
//            $this->map[$client->resourceId] = [
//                'resourceId' => $client->resourceId,
//                'roomId' => $roomId,
//                'client' => $client,
//            ];
//        }
//    }
//
//    public function getMethod($json)
//    {
//        if($message = @json_decode($json, true)) {
//            return $message['method'] ?? null;
//        }
//        return null;
//    }
//
//    public function getClientByResourceId($resourceId = null)
//    {
//        return $this->map[$resourceId]['client'] ?? null;
//    }
//
//    public function getClientByRoomId($roomId = null, $clinicId = null)
//    {
//        $room = Cabinet::findOne(['mis_id' => $roomId]);
//
//        if(!$room || !$this->map) return null;
//        if($room->building && $room->building->clinic_id == $clinicId) {
//            foreach($this->map as $item) {
//                if($item['roomId'] == $room->unique_id) return $item['client'];
//            }
//        }
//
//
//        return null;
//    }
//
//    public function getRoomIdFromMap($resourceId)
//    {
//        return $this->map[$resourceId]['roomId'] ?? null;
//    }














}
