<?php

namespace common\components;

use yii\base\Model;
use common\components\RnovaApi;

class Api
{
    private $api;

    const STATUS_ID_WRITED = 1;     // записан
    const STATUS_ID_WAIT   = 2;     // ожидает
    const STATUS_ID_BUSY   = 3;     // на приеме  upcoming

    /**
     *
     */
    public function __construct()
    {
        $this->api = new RnovaApi($_ENV['MIS_REQUEST_API_URL'], $_ENV['MIS_API_KEY']);
    }

    /**
     * @param array $params
     * @param bool $statuses
     * @return array
     */
    public function getAppointments($params = [], $statuses = true)
    {
        if($statuses) {
            $params = array_merge($params, ['status_id' => $statuses]);
        }
        return $this->api->getRequest('getAppointments', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getSchedule($params = [])
    {
        return $this->api->getRequest('getSchedule', $params);
    }

    /**
     * @param $params
     * @return array
     */
    public function createAppointment($params)
    {
        $data =  $this->api->getRequest('createAppointment', $params);
        return $data;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getUsers($params = [])
    {
        return $this->api->getRequest('getUsers', $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getClinics($params = [])
    {
        return $this->api->getRequest('getClinics', $params);
    }

    /**
     * return array user schedules
     * @param $schedule
     * @return bool
     */
    public function getDataFromRequest($appointment, $schedule)
    {
        $appointmentData = $appointment['data'] ?? [];
        $scheduleData = $schedule['data'] ?? [];
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

    /**
     * @return bool
     */
    public function getUserData()
    {
        $appointmentsJson = $this->getAppointments();
        $schedulesJson = $this->getSchedule();
        if(($appointment = ApiHelper::getDataFromApi($appointmentsJson)) && ($schedule = ApiHelper::getDataFromApi($schedulesJson))) {
            return $this->getDataFromRequest($appointment, $schedule);
        }
        return false;
    }


    /**
     * @param $userId
     * @return bool
     */
    public function getUserById($userId)
    {
        if(($userDataResponse = $this->api->getRequest('getUsers', ['user_id' => $userId])) && ($userData = ApiHelper::getDataFromApi($userDataResponse))) {
            return $userData[0];
        }
        return false;
    }
}
