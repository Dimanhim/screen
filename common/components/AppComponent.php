<?php

namespace common\components;

use Yii;
use common\models\Cabinet;
use yii\base\Component;

class AppComponent extends Component
{
    private $clinic;
    private $clinics;

    private $apiEvent;
    private $apiData;

    private $room;
    private $appointments;
    private $preparedAppointments;
    private $patientIds;
    private $patients;
    private $users;
    private $user;

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


    public function setRoom($roomId)
    {
        $this->room = Cabinet::getByUniqueId($roomId);
    }


    public function setPatientIds()
    {
        if(!$this->appointments) return false;

        $this->patientIds = array_map(function($n) {
            return $n['patient_id'];
        }, $this->appointments);
    }

    public function setUsers()
    {
        $request = Yii::$app->api->getUsers();
        if($data = ApiHelper::getDataFromApi($request)) {
            foreach($data as $user) {
                $this->users[$user['id']] = $user;
            }
        }
    }
    public function setUserBySchedule()
    {
        if(!$this->users || !$this->room) return false;

        $params = [
            'time_start' => date('d.m.Y') . ' 00:00',
            'time_end' => date('d.m.Y') . ' 23:59',
            'type' => 1
        ];
        $request = Yii::$app->api->getSchedulePeriods($params);
        if($data = ApiHelper::getDataFromApi($request)) {
            foreach($data as $period) {
                if($period['room'] == $this->room->mis_id) {
                    if($user = $this->getUserById($period['user_id'])) {
                        $this->user = $user;
                        return;
                    }
                }
            }
        }
    }




    public function getPatientById($patientId = null)
    {
        return $this->patients[$patientId] ?? null;
    }
    public function getUserById($userId = null)
    {
        return $this->users[$userId] ?? null;
    }


    public function getScreenAppointments($roomId)
    {
        $this->setRoom($roomId);

        if(!$this->room) return false;

        $params = [
            'date_from' => date('d.m.Y') . ' 00:00',
            'date_to' => date('d.m.Y') . ' 23:59',
            'status_id' => Api::STATUS_ID_WAIT . ',' . Api::STATUS_ID_BUSY,
            'room' => $this->room->mis_id,
        ];

        $request = Yii::$app->api->getAppointments($params);
        $this->appointments = ApiHelper::getDataFromApi($request);
        $this->prepareAppointments();
        return $this->preparedAppointments;

    }
    public function getRoomInfo($roomId)
    {
        $this->setRoom($roomId);
        $this->setUsers();

        if(!$this->room) return false;

        $this->setUserBySchedule();

        if(!$this->user) return false;

        $data = $this->user;
        $data['name'] = $this->room->mis_id;
        $data['number'] = $this->room->number;
        $data['doctorName'] = $this->user['name'];
        $data['professionsText'] = $this->user['profession_titles'];
        return $data;
    }

    private function prepareAppointments() {

        $this->setPatientIds();

        if(!$this->patientIds) return false;

        $request = Yii::$app->api->getPatient(['id' => implode(',', $this->patientIds)]);
        $patientData = ApiHelper::getDataFromApi($request);


        if($patientData) {
            if(isset($patientData['patient_id'])) {
                $this->patients[$patientData['patient_id']] = $patientData;
            }
            elseif(count($patientData) > 1) {
                foreach($patientData as $value) {
                    $this->patients[$value['patient_id']] = $value;
                }
            }
        }

        if($this->appointments && $this->patients) {
            foreach($this->appointments as $k => $appointment) {
                $patient = $this->getPatientById($appointment['patient_id']);
                $preparedAppointments = $appointment;
                $preparedAppointments['time_start'] = date('H:i', strtotime($appointment['time_start']));
                $preparedAppointments['patientNumber'] = null;
                $preparedAppointments['ticketCode'] = null;
                if($patient) {
                    $preparedAppointments['patientNumber'] = $patient['number'];
                }
                $this->preparedAppointments[] = $preparedAppointments;
            }
        }
    }
}
