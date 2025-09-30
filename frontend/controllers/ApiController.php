<?php

namespace frontend\controllers;

use common\components\ApiHelper;
use common\components\SocketHandler;
use common\models\Cabinet;
use frontend\components\ScreenSocket;
use common\controllers\ApiBaseController;
use Yii;


/**
 *
 */
class ApiController extends ApiBaseController
{
    public function actionHandle()
    {
        $data = Yii::$app->request->bodyParams;

        if(!isset($data['data'])) {
            $this->addError(403, 'Неверный формат данных');
            return $this->response();
        }
        Yii::$app->app->setData($data['data']);

        Yii::$app->app->handleWebhook();

        return $this->response();
    }

    public function actionGetRoom()
    {
        $data = Yii::$app->app->getRoomInfo(Yii::$app->request->post('roomId'));

        /*$data = [
            'name' => 'Кабинет УЗИ',
            'doctorName' => 'Иванов Иван Иванович',
            'avatar' => 'https://files.rnova.org/198733bd446bb513a3bfe91ae1f3d391/2f3988fbcf0519ea27fdcefaf0d1772d.png',
            'professionsText' => 'Врач высшей категории',
        ];*/
        return $this->response($data);
    }

    public function actionGetAppointments()
    {
        $data = Yii::$app->app->getScreenAppointments(Yii::$app->request->post());

//        $data = [
//             [
//                 'id' => 1111,
//                 'status_id' => 2,
//                 'time_start' => '15:00',
//                 'patientNumber' => '555',
//                 'ticketCode' => 'Л001',
//             ],
//             [
//                 'id' => 2222,
//                 'status_id' => 3,
//                 'time_start' => '15:31',
//                 'patientNumber' => '444',
//                 'ticketCode' => 'Л002',
//             ],
//             [
//                 'id' => 5555,
//                 'status_id' => 2,
//                 'time_start' => '16:00',
//                 'patientNumber' => '333',
//                 'ticketCode' => 'Л003',
//             ],
//        ];
        return $this->response($data);
    }











}

