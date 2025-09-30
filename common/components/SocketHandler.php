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

    public function init()
    {
        return parent::init();
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $this->handleMessage($msg, $from);
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
        if (!$data || !isset($data['method'])) {
            return false;
        }

        switch ($data['method']) {
            case 'register': {
                $this->registerUser($data, $client);
            }
                break;
            case 'update' : {
                if(isset($data['data'])) {
                    $this->updateScreen($data['data']);
                }
            }
                break;
            case 'notification':
                if(isset($data['data'])) {
                    $this->inviteScreen($data['data']);
                }
                break;
        }
        return true;
    }

    public function getMessage($method, $message)
    {
        $methods = ['register', 'update', 'notification'];
        if(!in_array($method, $methods)) return null;

        return json_encode([
            'method' => $method,
            'data' => $message,
        ]);
    }



    private function registerUser($data, $client)
    {
        if(!isset($data['roomId'])) return false;

        self::$clients[$data['roomId']] = $client;

    }

    private function updateScreen($data)
    {
        $roomId = $data['roomId'] ?? null;

        $client = self::$clients[$roomId] ?? null;

        if(!$client) return false;

        $client->send($this->getMessage('update', $data));
    }

    private function inviteScreen($data)
    {
        $roomId = $data['roomId'] ?? null;

        $client = self::$clients[$roomId] ?? null;

        if(!$client) return false;

        $client->send($this->getMessage('notification', $data));
    }

    public static function sendMessage($message)
    {
        if (!is_string($message)) {
            $message = json_encode($message);
        }
        try {
            $client = new Client(
                \Yii::$app->params['socket']['url']
            );
            $client->send($message);

        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}
