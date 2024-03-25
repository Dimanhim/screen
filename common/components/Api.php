<?php

namespace common\components;

use yii\base\Model;

class Api extends RnovaApi
{
    //private $mis_id;
    private $status_id = '1,2,3';

    const STATUS_ID_WRITED = 1;     // записан
    const STATUS_ID_WAIT   = 2;     // ожидает
    const STATUS_ID_BUSY   = 3;     // на приеме  upcoming


    /**
    Семейная клиника
     */
    //private $clinic_id = 290;
    //private $clinic_id = 1188;        // с altermed
    //private $clinic_id = 2;

    public function init()
    {
        parent::init();

    }

    public function getAppointments($params = [])
    {
        /*$params = [
            'clinic_id' => $this->clinic_id,
            'date_from' => $this->time_start,
            'date_to' => $this->time_end,
            'room' => $this->mis_id,
            'show_busy' => 1,
            'status_id' => $this->status_id,
        ];
        if(!$time) {
            unset($params['date_from']);
            unset($params['date_to']);
        }*/
        $params = array_merge($params, ['status_id' => $this->status_id]);
        return $this->getRequest('getAppointments', $params);
    }

    public function getSchedule($params = [])
    {
        /*$params = [
            'clinic_id' => $this->clinic_id,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'room' => $this->mis_id,
            'show_busy' => 1,
        ];
        if(!$time) {
            unset($params['time_start']);
            unset($params['time_end']);
        }*/
        return $this->getRequest('getSchedule', $params);
    }

    public function createAppointment($params)
    {
        $data =  $this->getRequest('createAppointment', $params);
        \Yii::$app->infoLog->add('$data', $data, 'api-appointment.txt');
        return $data;
    }

    public function getUsers($params = [])
    {
        return $this->getRequest('getUsers', $params);
    }

    public function getClinics($params = [])
    {
        return $this->getRequest('getClinics', $params);
    }

    /**
     * return array user schedules
     * @param $schedule
     * @return bool
     */
    public function getDataFromRequest($appointment, $schedule)
    {
        $appointmentData = array_key_exists('data', $appointment) ? $appointment['data'] : [];
        $scheduleData = array_key_exists('data', $schedule) ? $schedule['data'] : [];
        if(!empty($appointmentData)) {
            foreach($appointmentData as $userId => $data) {
                $user = $this->getUserById($data['doctor_id']);
                return [
                    'user' => $user,
                    'schedules' => $appointmentData,
                    'all_schedules' => $scheduleData,
                ];
            }
        }
        elseif(!empty($scheduleData)) {
            foreach($scheduleData as $userId => $scheduleData) {
                $user = $this->getUserById($userId);
                return [
                    'user' => $user,
                    'schedules' => $appointmentData,
                    'all_schedules' => $scheduleData,
                ];
            }
        }
    }

    public function getUserData()
    {
        $appointmentsJson = $this->getAppointments();
        $schedulesJson = $this->getSchedule();
        if(($appointment = ApiHelper::getDataFromApi($appointmentsJson)) && ($schedule = ApiHelper::getDataFromApi($schedulesJson))) {
            return $this->getDataFromRequest($appointment, $schedule);
        }
        return false;
    }


    public function getUserById($userId)
    {
        if(($userDataResponse = $this->getRequest('getUsers', ['user_id' => $userId])) && ($userData = ApiHelper::getDataFromApi($userDataResponse))) {
            return $userData[0];
        }
        return false;
    }






















}
