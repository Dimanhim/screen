<?php

namespace common\components;

use yii\base\Component;

class AppComponent extends Component
{
    private $clinic;
    private $clinics;

    public function init()
    {
        return parent::init();
    }

    public function setClinics()
    {
        $data = \Yii::$app->api->getClinics();
        if($data && $data['data']) {
            $this->clinics = $data['data'];
        }
    }

    public function getClinicList()
    {
        $data = [];
        if($this->clinics) {
            foreach($this->clinics as $clinic) {
                $data[$clinic['id']] = $clinic['title'];
            }
        }
        return $data;
    }

    public function setClinicById($clinicId = null)
    {
        if($clinicId && $this->clinics) {
            foreach($this->clinics as $clinic) {
                if($clinicId == $clinic['id']) {
                    $this->clinic = $clinic;
                    return;
                }
            }
        }

        $this->clinic = null;
    }

    public function getClinic()
    {
        return $this->clinic;
    }

    public function getClinicTitle()
    {
        return $this->clinic['title'] ?? null;
    }
}
