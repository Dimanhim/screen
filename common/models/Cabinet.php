<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "cabinet".
 *
 * @property int $id
 * @property string $unique_id
 * @property string $number
 * @property string $name
 * @property int $mis_id
 * @property int $is_active
 * @property int $deleted
 * @property int $position
 * @property int $created_at
 * @property int $updated_at
 */
class Cabinet extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cabinet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'message' => 'Необходимо заполнить поле'],
            [['clinic_id'], 'integer'],
            [['number', 'name', 'mis_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $attributes = [
            'number' => 'Номер',
            'name' => 'Название',
            'clinic_id' => 'Корпус',
            'mis_id' => 'МИС ID',
        ];
        return array_merge(parent::attributeLabels(), $attributes);
    }

    public function getAccesses()
    {
        return $this->hasOne(UserAccess::className(), ['clinic_id' => 'clinic_id']);
    }

    public static function getList()
    {
        $cabinets = [];
        $cabinets['all'] = 'Все кабинеты';
        if($models = self::find()->asArray()->all()) {
            foreach($models as $model) {
                $cabinets[$model['id']] = $model['name'];
            }
        }
        return $cabinets;
    }

    /*public static function findModels()
    {
        return parent::findModels()->andWhere(['clinic_id' => $this]);
    }*/

    public function getClinicName()
    {
        if($clinics = Yii::$app->accesses->getClinics()) {
            foreach($clinics as $clinic) {
                if($this->clinic_id == $clinic['id']) return $clinic['title'];
            }
        }
        return false;
    }

    public function clinicList()
    {
        $data = [];
        if($clinics = Yii::$app->accesses->getClinics()) {
            foreach($clinics as $clinic) {
                $data[$clinic['id']] = $clinic['title'];
            }
        }
        return $data;
    }

    /**
     * @param $name
     * @return array
     */
    public static function getNamesOfUser($name)
    {
        $result = [
            'surname' => null,
            'name' => null,
        ];
        $namesArray = explode(' ', $name);
        if(count($namesArray) >=3) {
            $result['surname'] = $namesArray[0];
            $result['name'] = $namesArray[1].' '.$namesArray[2];
        }
        else {
            $result['surname'] = $name;
        }
        return $result;
    }

    public static function getSchedules($schedules)
    {
        $scheduleArray = ['buzy' => [], 'await' => []];
        $api = new Api();
        //$scheduleArray = [];
        if($schedules) {
            foreach ($schedules as $schedule) {

                // из ответа
                $startTimestamp = strtotime($schedule['time_start']);
                $endTimestamp = strtotime($schedule['time_end']);

                // текущие
                $userStartTimestamp = strtotime($api->getTimeStart());
                $userEndTimestamp = strtotime($api->getTimeEnd());
                $userNowTimestamp = strtotime($api->getTimeNow());
                $userDateTimestamp = strtotime(date('d.m.Y', $userStartTimestamp));
                //$userNowTimestamp = strtotime(date('d.m.Y', $userStartTimestamp));

                $dayTimestamp = strtotime(date('d.m.Y', $startTimestamp));
                $startStringStart = self::getTimeAsString( $startTimestamp - $dayTimestamp );
                $startStringEnd = self::getTimeAsString( $endTimestamp - $dayTimestamp );

                if(
                    $schedule['status_id'] == Api::STATUS_ID_BUSY
                    //$startTimestamp < $userNowTimestamp
                    //&&
                    //$endTimestamp > $userNowTimestamp
                    //&&
                    //$userDateTimestamp == $dayTimestamp

                ) {
                    $scheduleArray['buzy'][] = [
                        'visit_id' => $schedule['id'],
                        'time_from' => $startStringStart,
                        'time_to' => $startStringEnd,
                    ];
                }
                elseif(
                    //$schedule['status_id'] == Api::STATUS_ID_WRITED ||
                    $schedule['status_id'] == Api::STATUS_ID_WAIT
                    //$startTimestamp > $userNowTimestamp
                    //&&
                    //$userDateTimestamp == $dayTimestamp
                ) {
                    $scheduleArray['await'][] = [
                        'visit_id' => $schedule['id'],
                        'time_from' => $startStringStart,
                        'time_to' => $startStringEnd,
                    ];
                }




                /*if($schedule['is_busy'] && $schedule['time_start_short'] && $schedule['time_end_short']) {
                    $timeStart = self::getSecondsInTime($schedule['time_start_short']);
                    $timeTo = self::getSecondsInTime($schedule['time_end_short']);

                    $timeNow = strtotime($api->getTimeStart()) - strtotime($api->getDate());
                    $dateNow = strtotime($api->getDate());
                    if(
                        $timeStart > $timeNow
                        && $dateNow == strtotime($schedule['date'])
                    ) {
                        $scheduleArray['await'][] = [
                            'visit_id' => null,
                            'time_from' => $schedule['time_start_short'],
                            'time_to' => $schedule['time_end_short'],
                        ];
                    }

                    // на приеме
                    elseif (
                        $timeStart < $timeNow &&
                        $timeTo > $timeNow
                        && $dateNow == strtotime($schedule['date'])
                    ) {
                        $scheduleArray['buzy'][] = [
                            'visit_id' => null,
                            'time_from' => $schedule['time_start_short'],
                            'time_to' => $schedule['time_end_short'],
                        ];
                    }
                }*/
            }
        }
        return $scheduleArray;
    }

    public static function getSecondsInTime($time)
    {
        $seconds = 0;
        $arr = explode(':', $time);
        $seconds += $arr[0] * 60 * 60;
        $seconds += $arr[1] * 60;
        return $seconds;
    }
    public static function getTimeAsString($time)
    {
        if($time) {
            $hours = floor($time / 60 / 60);
            $diff = $time - $hours * 60 * 60;
            $minutes = floor($diff / 60);
            return str_pad($hours, 2, 0, STR_PAD_LEFT).':'.str_pad($minutes, 2, 0, STR_PAD_LEFT);
        }
        return 0;
    }

    public static function getViewSvg()
    {
       return '
       <svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path></svg>
       ';
    }

    public function getDeleteSvg()
    {
        return '
        <svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg>
        ';
    }

    public function tooltipText()
    {
        $str = '<div class="tooltip-view-links">';
        $str .= '<div>'.Html::a('Кабинет врача', ['../cabinet/'.$this->id, 'type' => 'cabinet'], ['target' => '_blanc']).'</div>';
        $str .= '<div>'.Html::a('Очередь талонов', ['../cabinet/'.$this->id, 'type' => 'ticket'], ['target' => '_blanc']).'</div>';
        $str .= '</div>';
        return $str;
    }
}
