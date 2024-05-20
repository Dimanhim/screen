<?php

namespace backend\controllers;

use backend\models\FormTicket;
use common\models\Ticket;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class AjaxController extends Controller
{
    public $res = ['result' => 0, 'message' => null, 'html' => null, 'data' => []];
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
            'mis_id' => Yii::$app->request->post('mis_id'),
            'cabinet_id' => Yii::$app->request->post('cabinet_id')
        ]);
        if($html = $model->getAppointmentListHtml()) {
            $this->res['result'] = 1;
            $this->res['html'] = $html;
        }

        return $this->res;
    }

    public function actionSubmitTicketForm()
    {
        $model = new FormTicket();
        if($model->load(Yii::$app->request->post())) {
            if($model->validateTicketForm()) {
                if($model->saveValues()) {
                    if($model->clinic_id and $model->room) {
                        $this->res['result'] = 1;
                        $this->res['message'] = $model->model ? $model->model->ticket : null;
                        $this->res['data'] = ['clinic_id' => $model->clinic_id, 'room' => $model->room, 'cabinet' => $model->cabinet_id];
                    }
                }
            }


            \Yii::$app->infoLog->add('model errors', $model->_errors);
            if($model->_hasErrors()) {
                $this->res['result'] = 0;
                $this->res['data'] = null;
                $model_errors = $model->_errorSummary();
                $this->res['message'] = $model_errors;
            }
        }
        return $this->res;
    }







}
