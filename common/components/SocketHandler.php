<?php

namespace common\components;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SocketHandler implements MessageComponentInterface
{
    protected $clients = [];
    /*public $map = [
        [
            'roomId' => null,
            'resourceId' => null,
            'client' => null,
        ],
        ...
    ];*/

    public $map = [];

    //public function __construct() {
        //$this->clients = new \SplObjectStorage;
    //}

    public function onOpen(ConnectionInterface $conn) {
        //$this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo 'message from - ' . $from->resourceId;
        echo 'message - ' . $msg;
        $from->send('some data');
    }

    public function onClose(ConnectionInterface $conn) {
        echo "close";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "error";
    }

    public function getRoomIdByClientId($clientId = null)
    {
        if(!$this->map || !$clientId) return null;
        foreach($this->map as $map) {
            if($map['resourceId'] == $clientId) return $map['roomId'];
        }
        return null;
    }

    public function getClientIdByRoomId($clientId = null)
    {
        if(!$this->map || !$clientId) return null;
        foreach($this->map as $map) {
            if($map['resourceId'] == $clientId) return $map['roomId'];
        }
        return null;
    }

}
