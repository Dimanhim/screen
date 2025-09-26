<?php

namespace common\components;

use common\models\Cabinet;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use yii\base\Component;

class SocketHandler extends Component implements MessageComponentInterface
{
    public $clients = [];
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
        if(!$this->clients) {
            $this->clients = new \SplObjectStorage;
        }
        return parent::init();
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo 'message from - ' . $from->resourceId . ' ';
        echo 'message - ' . $msg . ' ';
        $method = $this->getMethod($msg);
        $from->send('some data');
        echo 'method - ' . $method . ' ';
        /*if($this->getMethod($msg) == 'register') {
            $this->setToMap($from, $msg);
        }*/

        foreach($this->clients as $client) {
            $client->send('some client data !');
        }
    }

    public function sendByRoomId($roomName, $message)
    {
        $json = json_encode($message);
        $room = Cabinet::findOne(['mis_id' => $roomName]);
        // здесь проверить клинику

        foreach($this->clients as $client) {
            $client->send($json);
        }


    }

    public function setToMap($client, $json)
    {
        if($message = @json_decode($json, true)) {
            $roomId = $message['roomId'];
            echo 'roomId - '.$roomId . ' ';
            echo 'resourceId - '.$client->resourceId . ' ';
            if(!$roomId || !$client->resourceId) return false;

            $this->map[$client->resourceId] = [
                'resourceId' => $client->resourceId,
                'roomId' => $roomId,
                'client' => $client,
            ];
        }
    }

    public function getMethod($json)
    {
        if($message = @json_decode($json, true)) {
            return $message['method'] ?? null;
        }
        return null;
    }

    public function getClientByResourceId($resourceId = null)
    {
        return $this->map[$resourceId]['client'] ?? null;
    }

    public function getClientByRoomId($roomId = null, $clinicId = null)
    {
        $room = Cabinet::findOne(['mis_id' => $roomId]);

        if(!$room || !$this->map) return null;
        if($room->building && $room->building->clinic_id == $clinicId) {
            foreach($this->map as $item) {
                if($item['roomId'] == $room->unique_id) return $item['client'];
            }
        }


        return null;
    }

    public function getRoomIdFromMap($resourceId)
    {
        return $this->map[$resourceId]['roomId'] ?? null;
    }










    public function onClose(ConnectionInterface $conn) {
        echo "close";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "error";
    }



}
