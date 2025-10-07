<?php

namespace common\components;

use common\models\Ticket;
use Yii;
use common\models\Cabinet;
use yii\base\Component;

class AppComponent extends Component
{
    const STATUS_WAIT = 2;
    const STATUS_BUSY = 3;

    private $clinicIds = [];
    private $clinic;
    private $clinics;

    private $apiData;

    private $room;
    private $tickets;
    private $professions;
    private $appointments;
    private $preparedAppointments;
    private $patientIds;
    private $patients;
    private $users;
    private $user;                  // врач, который устанавливается по названию кабинета из расписания

    public function init()
    {
        return parent::init();
    }

    public function setClinics()
    {
        $data = \Yii::$app->api->getClinics();
        if($clinics = ApiHelper::getDataFromApi($data)) {
            $this->clinics = $clinics;
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

    public function setData($data)
    {
        $this->apiData = isset($data[0]) && count($data) == 1 ? $data[0] : $data;
    }

    public function getData()
    {
        return $this->apiData;
    }

    public function setClinicIds()
    {
        if($this->clinicIds) return;

        $sql = "
            SELECT bld.clinic_id FROM ".Yii::$app->db->tablePrefix."cabinet AS cab
            LEFT JOIN ".Yii::$app->db->tablePrefix."buildings AS bld ON bld.id = cab.building_id
            WHERE bld.is_active = 1 AND cab.is_active = 1 AND bld.deleted IS NULL AND cab.deleted IS NULL AND bld.clinic_id IS NOT NULL
        ";
        $query = Yii::$app->db->createCommand($sql)->queryAll();
        if($query) {
            $clinicIds = array_map(function($n) {
                return $n['clinic_id'];
            }, $query);
            $this->clinicIds = array_unique($clinicIds);
        }
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

    private function setUsers()
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
        if(!$this->users || !$this->room || !$this->clinicIds) return false;

        $params = [
            'time_start' => date('d.m.Y') . ' 00:00',
            'time_end' => date('d.m.Y') . ' 23:59',
            'clinic_id' => implode(',', $this->clinicIds),
            'type' => 1
        ];
        $request = Yii::$app->api->getSchedulePeriods($params);
        if($data = ApiHelper::getDataFromApi($request)) {
            foreach($data as $period) {
                if(!Helpers::isInTimeNow($period['time_start'], $period['time_end'])) continue;

                if($period['room'] == $this->room->mis_id) {
                    if($user = $this->getUserById($period['user_id'])) {
                        $this->user = $user;
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function getPatientById($patientId = null)
    {
        return $this->patients[$patientId] ?? null;
    }
    public function getUserById($userId = null)
    {
        return $this->users[$userId] ?? null;
    }


    public function getScreenAppointments($postRequest)
    {
        if(!isset($postRequest['roomId']) || !isset($postRequest['doctorId'])) return null;

        $this->setClinicIds();
        $this->setRoom($postRequest['roomId']);

        if(!$this->room || !$this->room->building) return false;

        $params = [
            'date_from' => date('d.m.Y') . ' 00:00',
            'date_to' => date('d.m.Y') . ' 23:59',
            'doctor_id' => $postRequest['doctorId'],
            'clinic_id' => $this->room->building->clinic_id,
            'status_id' => Api::STATUS_ID_WAIT . ',' . Api::STATUS_ID_BUSY,
            'room' => $this->room->mis_id
        ];

        $request = Yii::$app->api->getAppointments($params);
        $this->appointments = ApiHelper::getDataFromApi($request);
        $this->prepareAppointments();
        return $this->preparedAppointments;

    }

    private function setProfessions()
    {
        $request = Yii::$app->api->getProfessions(['show_all' => true]);

        $data = ApiHelper::getDataFromApi($request);

        if($data) {
            foreach($data as $item) {
                $this->professions[$item['id']] = $item;
            }
        }
    }

    private function setTickets()
    {
        if(!$this->room) return null;
        $timeStart = strtotime(date('d.m.Y'));
        $timeEnd = $timeStart + 86400 - 1;

        $tickets = Ticket::find()
            ->where(['mis_id' => $this->room->mis_id])
            ->andWhere(['between', 'time_start_ts', $timeStart, $timeEnd])
            ->andWhere(['is_active' => 1])
            ->andWhere(['deleted' => null])
            ->all();
        if(!$tickets) return null;

        foreach($tickets as $ticket) {
            $this->tickets[$ticket->appointment_id] = $ticket;
        }
    }

    public function getProfessionDoctor($professionId = null)
    {
        if(!$this->professions || !isset($this->professions[$professionId])) return null;

        return $this->professions[$professionId]['doctor_name'];
    }

    public function handleWebhook()
    {
        if(!$this->apiData) return false;

        $this->appointments = [$this->apiData];

        $roomId = $this->getRoomId();
        $this->setRoom($roomId);
        $this->prepareAppointments();
        $this->apiData = $this->preparedAppointments[0] ?? null;

        if(!$this->apiData || !$roomId) return false;

        $this->apiData['roomId'] = $roomId;

        $method = 'update';

        if($this->apiData['status_id'] == self::STATUS_BUSY) {
            $method = 'notification';
        }

        $data = [
            'method' => $method,
            'data' => $this->apiData
        ];

        return SocketHandler::sendMessage(json_encode($data));
    }

    public function getRoomId()
    {
        $room = Cabinet::getByRoomName($this->apiData['room']);
        if(!$room) return null;

        return $room->unique_id;
    }

    public function getRoomInfo($roomId)
    {
        $this->setClinicIds();
        $this->setRoom($roomId);
        $this->setUsers();
        $this->setProfessions();

        if(!$this->room) return false;

        $this->setUserBySchedule();

        if(!$this->user) return false;

        $data = $this->user;
        $data['name'] = $this->room->mis_id;
        $data['number'] = $this->room->number;
        $data['doctorName'] = $this->user['name'];
        $data['professionsText'] = $this->getProfessionTitle($this->user['profession']);
        return $data;
    }

    private function prepareAppointments() {

        $this->setPatientIds();
        $this->setTickets();

        if(!$this->patientIds) return false;

        sleep(1);
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
                $preparedAppointments['ticketCode'] = $this->getTicketCode($appointment['id']);
                if($patient) {
                    $preparedAppointments['patientNumber'] = $patient['number'];
                    $preparedAppointments['patient_short_name'] = $patient['first_name'] . ' '.$patient['third_name'];
                }
                $this->preparedAppointments[] = $preparedAppointments;
            }
        }
    }

    private function getTicketCode($appointmentId)
    {
        if(!$this->room) return null;
        if(!$this->room->show_tickets) return null;

        $ticket = $this->tickets[$appointmentId] ?? null;

        if(!$ticket) return null;

        return $ticket->ticket;
    }

    public function getProfessionTitle($professions = [])
    {
        if(!$professions) return null;

        $key = array_key_first($professions);

        return $this->getProfessionDoctor($professions[$key]);
    }
}
