<?php

namespace frontend\controllers;

use common\components\SocketHandler;
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
        if(!isset($data['event'])) {
            $this->addError(403, 'Не указано событие');
            return $this->response();
        }
        if(!isset($data['data'])) {
            $this->addError(403, 'Неверный формат данных');
            return $this->response();
        }
        Yii::$app->app->setEvent($data['event']);
        Yii::$app->app->setData($data['data']);

        $socket = new SocketHandler();



    }

    public function actionGetAppointments()
    {

    }











}

