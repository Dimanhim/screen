<?php

namespace backend\controllers;

use common\models\Ticket;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class AjaxController extends Controller
{
    public $res = ['result' => 0, 'message' => null, 'html' => null];
    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionGetAppointmentList()
    {
        $model = new Ticket([
            'clinic_id' => Yii::$app->request->post('clinic_id'),
            'mis_id' => Yii::$app->request->post('mis_id')
        ]);

        if($html = $model->getAppointmentListHtml()) {
            $this->res['result'] = 1;
            $this->res['html'] = $html;
        }

        return $this->res;
    }

    public function actionShowAppointment()
    {
        if(!$appointment_id = Yii::$app->request->post('appointment_id')) return $this->res;
        $model = new Document(['appointment_id' => $appointment_id]);
        $appointment = $model->getAppointment();
        if(!$appointment or $appointment['error']) {
            $this->res['message'] = $model->getAppointmentErrorMessage();
            return $this->res;
        }
        if(isset($appointment['data']) and isset($appointment['data'][0])) {
            $appointment = $appointment['data'][0];
            $patient_id = $appointment['patient_id'];
            if($patient = $model->getPatient($patient_id)) {
                $patientName = $patient['patient_name'];
                $patientBirthDate = $patient['patient_birthdate'];
                $patientMessage = $patientName.', '.$patientBirthDate.' г.р.';
                $this->res['html'] = $model->getAppointmentSuccessMessage($patientMessage);
                $this->res['result'] = 1;
                return $this->res;
            }
        }
        $this->res['message'] = $model->getAppointmentErrorMessage();
        return $this->res;
    }
}
