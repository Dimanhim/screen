<?php

namespace frontend\controllers;

use backend\controllers\BaseController;
use common\components\Api;
use common\components\ApiHelper;
use common\models\Cabinet;
use common\models\Ticket;
use Yii;
use yii\filters\ContentNegotiator;
use yii\helpers\Html;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\Cors;

/**
 *
 */
class ApiController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'corsFilter' => [
                'class' => Cors::class,
            ],
            'className' => Cabinet::className(),
        ];
    }

    public function actionGetAppointments()
    {
        $today_start = date('d.m.Y').' 00:00';
        $today_end = date('d.m.Y').' 23:59';

        $room_id = Yii::$app->request->get('room');

        if(!$cabinet = Cabinet::findOne($room_id)) {
            $this->_addError('Запрошенный кабинет не существует');
            return $this->response();
        }

        $data = $cabinet->prepareJsonDataForScreen($today_start, $today_end);
        $this->_addError($data['error']);

        return $this->response($data['data']);
    }

    public function actionGetAppointmentsTest()
    {
        $data = ApiHelper::testApi();

        return $this->response($data);
    }






    private function response($data = [])
    {
        if(!$this->_hasErrors()) {
            $this->_data['data'] = $data;
        }
        else {
            $this->_data['error'] = 1;
            $this->_data['message'] = $this->_errorSummary();
        }

        $this->response->data = $this->_data;
        return $this->response->data;
    }











}

