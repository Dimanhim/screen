<?php

namespace common\models;

use common\components\Helpers;
use common\components\ApiHelper;
use Yii;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $clinic_id
 * @property string|null $mis_id
 * @property int|null $time_start
 * @property string|null $patient_name
 * @property int|null $appointment_id
 * @property string|null $ticket
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Ticket extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tickets}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['clinic_id', 'time_start', 'appointment_id'], 'integer'],
            [['mis_id', 'patient_name', 'ticket'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'clinic_id' => '№ корпуса',
            'mis_id' => '№ кабинета',
            'time_start' => 'Время приема',
            'patient_name' => 'Имя',
            'appointment_id' => 'ID Визита',
            'ticket' => 'Талон',
        ]);
    }

    public function getAppointmentListHtml()
    {
        return Yii::$app->controller->renderPartial('//ticket/_appointment_list', [
            'model' => $this,
            'list' => true,
        ]);
    }

    public function ticketList()
    {
        if(!$this->clinic_id and !$this->mis_id) return false;
        $params_shedule = [
            'clinic_id' => $this->clinic_id,
            'room' => $this->mis_id,
            'time_start' => $this->_today_start,
            'time_end' => $this->_today_end,
            'show_busy' => 1,
        ];
        $params_appointments = [
            'clinic_id' => $this->clinic_id,
            'date_from' => $this->_today_start,
            'date_to' => $this->_today_end,
            'room' => $this->mis_id,
            'show_busy' => 1,
        ];
        $schedulesData = ApiHelper::getDataFromApi(Yii::$app->api->getSchedule($params_shedule));
        $appointmentsData = ApiHelper::getDataFromApi(Yii::$app->api->getAppointments($params_appointments));
        //\Yii::$app->infoLog->add('$appointmentsData', $appointmentsData, 'appointments-data-log.txt');
        return $this->getTotalData($schedulesData, $appointmentsData);
    }

    public function getTotalData($schedulesFullData, $appointmentsData)
    {
        if(!$schedulesFullData) return false;
        foreach($schedulesFullData as $schedulesFullItem) {
            $schedulesData = $schedulesFullItem;
            break;
        }

        $data = [];
        foreach($schedulesData as $scheduleItem) {
            $appointmentItem = $this->getAppointmentItem($scheduleItem, $appointmentsData);
            $data[] = [
                'time_start' => Helpers::getTimeFromDatetime($scheduleItem['time_start']),
                'patient_name' => $appointmentItem ? $appointmentItem['patient_name'] : null,
                'visit_id' => $appointmentItem ? $appointmentItem['id'] : null,
                'ticket' => $appointmentItem ? $this->ticketTimes($appointmentItem) : null,
            ];
        }
        return $data;
    }

    // ЭТОТ МЕТОД НУЖНО ДОПИЛИТЬ
    // 17:30-17:45 закрывает сразу расписание на 17:00 - 17:30 и 17:30-18:00
    // а должен только 17:30 - 17:45
    public function getAppointmentItem($scheduleItem, $appointmentsData)
    {
        if(!$appointmentsData) return [];
        foreach($appointmentsData as $appointmentItem) {
            if($appointmentItem['clinic_id'] == $scheduleItem['clinic_id'] and $appointmentItem['room'] == $scheduleItem['room']) {
                $app_time_start = strtotime($appointmentItem['time_start']);
                $app_time_end = strtotime($appointmentItem['time_end']);

                $sch_time_start = strtotime($scheduleItem['time_start']);
                $sch_time_end = strtotime($scheduleItem['time_end']);

                // время визита между временем одной записи расписания
                if(Helpers::isDatesBetween($sch_time_start, $sch_time_end, $app_time_start)) {
                    return $appointmentItem;
                }
                // время начала визита входит в запись расписания,
                // но заканчивается позже
                if($app_time_start >= $sch_time_start and $app_time_end > $sch_time_end) {
                    return $appointmentItem;
                }

                // визит начался до текущей записи расписания
                // но время его окончания позже начала в расписании
                if($app_time_start < $sch_time_start and $app_time_end > $sch_time_start) {
                    return $appointmentItem;
                }
            }
        }
        return false;
    }

    public function ticketTimes($appointment)
    {
        $dateFrom = Helpers::getTimeFromDatetime($appointment['time_start']);
        $dateTo = Helpers::getTimeFromDatetime($appointment['time_end']);
        return $dateFrom . ' - ' . $dateTo;
    }
}
