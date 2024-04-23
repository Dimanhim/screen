<?php

namespace backend\models;

use common\components\ApiHelper;
use Yii;
use common\components\Helpers;
use common\models\Ticket;
use yii\base\Model;

class FormTicket extends Model
{
    public $_errors = [];

    public $model;

    public $first_name;
    public $last_name;
    public $third_name;
    public $birth_date;
    public $mobile;

    public $time_start;
    public $time_end;
    public $clinic_id;
    public $cabinet_id;
    public $doctor_id;
    public $room;

    public function rules()
    {
        return [
            [['first_name', 'mobile', 'time_start', 'time_end', 'clinic_id', 'cabinet_id', 'doctor_id', 'room'], 'required', 'message' => 'Поле не может быть пустым'],
            [['first_name', 'last_name', 'third_name', 'birth_date', 'mobile', 'time_start', 'time_end', 'clinic_id', 'room'], 'string', 'max' => 32],
        ];
    }

    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя пациента',
            'last_name' => 'Фамилия пациента',
            'third_name' => 'Отчество пациента',
            'birth_date' => 'Дата рождения',
            'mobile' => 'Номер телефона пациента',
            'time_start' => 'Дата и время начала',
            'time_end' => 'Дата и время окончания',
            'clinic_id' => 'ID клиники',
            'cabinet_id' => 'ID кабинета',
            'doctor_id' => 'ID врача',
            'room' => 'Кабинет',
        ];
    }

    public function saveValues()
    {
        $model = new Ticket();
        $model->patient_name = trim($this->first_name . ' ' . $this->last_name . ' '.$this->third_name);
        $model->clinic_id = $this->clinic_id;
        $model->mobile = Helpers::phoneFormat($this->mobile);
        $model->mis_id = $this->room;
        $model->setCabinetId();
        $this->cabinet_id = $model->cabinet_id;
        $model->time_start = $this->time_start;
        $model->time_end = $this->time_end;
        $model->setTicket();
        $this->model = $model;
        if($model->save()) {
            return $this->sendApiRequest($model);
        }
        else {
            $this->_addError($model->printErrorSummary());
        }
        return !$this->_hasErrors();
    }

    public function sendApiRequest(Ticket $model)
    {
        $params = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'third_name' => $this->third_name,
            'birth_date' => $this->birth_date,
            'mobile' => $this->mobile,
            'clinic_id' => $this->clinic_id,
            'doctor_id' => $this->doctor_id,
            'room' => $this->room,
            'time_start' => Helpers::dateTimeFormat($this->time_start),
            'time_end' => Helpers::dateTimeFormat($this->time_end),
        ];
        if($appointment = ApiHelper::getDataFromApi(Yii::$app->api->createAppointment($params))) {
            if(isset($appointment['error']) and $appointment['error'] and isset($appointment['message'])) {
                $this->_addError($appointment['message']);
            }
            if(!$this->_hasErrors()) {
                if(isset($appointment['data']) and $appointment['data']) {
                    $model->appointment_id = $appointment['data'];
                }
                elseif(!is_array($appointment) and $appointment) {
                    $model->appointment_id = $appointment;
                }
                else {
                    $this->_addError('Не удалось создать талон визита. Пожалуйста, попробуйте позднее');
                }
            }
            if(!$model->save()) {
                $this->_addError($model->printErrorSummary());
            }
        }
        return !$this->_hasErrors();


    }

    public function validateTicketForm()
    {
        if(!$this->first_name) {
            $this->_addError('Не заполнено имя');
            return false;
        }
        if(!$this->mobile) {
            $this->addError('Не заполнен номер телефона');
        }

        if(mb_strlen($this->first_name) < 3) $this->_addError('Имя должно содержать хотя бы 2 символа');
        $mobile = Helpers::phoneFormat($this->mobile, true);
        if($this->birth_date) {
            if($birthDateParts = explode('.', $this->birth_date)) {
                if(!isset($birthDateParts[0]) or !isset($birthDateParts[1]) or !isset($birthDateParts[2])) {
                    $this->_addError('Неверный формат даты рождения');
                }
                else {
                    if(mb_strlen($birthDateParts[0]) != 2) {
                        $this->_addError('Неверный формат даты рождения');
                    }
                    if(mb_strlen($birthDateParts[1]) != 2) {
                        $this->_addError('Неверный формат даты рождения');
                    }
                    if(mb_strlen($birthDateParts[2]) != 4) {
                        $this->_addError('Неверный формат даты рождения');
                    }
                }
            }
            else {
                $this->_addError('Неверный формат даты рождения');
            }
        }
        if(mb_strlen($mobile) != 11) $this->_addError('Введите корректный номер телефона');
        return !$this->hasErrors();
    }

    public function _hasErrors()
    {
        return !empty($this->_errors);
    }

    public function _addError($message)
    {
        if($message) {
            $this->_errors[] = $message;
        }
    }

    public function _errorSummary()
    {
        if($this->_errors) return implode(' ', $this->_errors);
        return false;
    }
}
