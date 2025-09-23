<?php

namespace common\components;

use yii\base\Component;
use yii\web\NotFoundHttpException;

class AppointmentComponent extends Component
{
    private $apiEvent;
    private $apiData;


    public function init()
    {
        return parent::init();
    }

    public function setEvent($event)
    {
        $this->apiEvent = $event;
    }

    public function setData($data)
    {
        $this->apiData = isset($data[0]) && count($data) == 1 ? $data[0] : $data;
    }

    public function getEvent()
    {
        return $this->apiEvent;
    }

    public function getData()
    {
        return $this->apiData;
    }











}

?>
