<?php

namespace frontend\controllers;

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

        return $this->response($data);
    }

    public function actionGetAppointments()
    {
        $data = Yii::$app->app->getScreenAppointments(Yii::$app->request->post());

        return $this->response($data);
    }

    public function actionGetUserUrl()
    {
        $url = '';
        $tempUrl = \Yii::$app->request->post('url');
        $pageFullContent = file_get_contents($tempUrl);
        $sym = explode('html', $pageFullContent);
        if(count($sym) >= 1) {
            $url = $sym[0].'html';
        }
        return $this->response($url);
    }











}

