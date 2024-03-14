<?php

namespace common\components;

use Yii;

class CabinetComponent
{
    const TYPE_DOCTOR_CABINET = 1;
    const TYPE_TICKETS_QUEUE  = 2;

    public $type;

    public function __construct()
    {
        $this->setType();
    }

    public function setType($type = self::TYPE_DOCTOR_CABINET)
    {
        $session = Yii::$app->session;
        if(!$session->get('component_type')) {
            $session->set('component_type', $type);
        }
        $this->type = $session->get('component_type');
    }

    public static function typeNames()
    {
        return [
            self::TYPE_DOCTOR_CABINET => 'Кабинет врача',
            self::TYPE_TICKETS_QUEUE  => 'Очередь талонов',
        ];
    }

    public function getTypeName()
    {
        return self::typeNames()[$this->type];
    }


}
