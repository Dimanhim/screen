<?php

namespace backend\controllers;

use common\models\Cabinet;
use common\models\Ticket;

class TicketController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'className' => Ticket::className(),
            ]
        );
    }

    /**
     * Lists all Cabinet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Ticket();

        $cabinets = Cabinet::findModels()->andWhere(['show_tickets' => 1])->all();

        return $this->render('index', [
            'model' => $model,
            'cabinets' => $cabinets,
        ]);
    }

    public function actionGenerateTicket($room, $time_start, $time_end, $patient_name, $mobile, $appointment_id)
    {
        $model = new Ticket();
        $model->setTicket();
        $model->mis_id = $room;
        $model->time_start = $time_start;
        $model->time_end = $time_end;
        $model->patient_name = $patient_name;
        $model->mobile = $mobile;
        $model->appointment_id = $appointment_id;
        if($cabinet = Cabinet::findOne(['mis_id' => $room])) {
            $model->cabinet_id = $cabinet->id;
        }
        if($model->save()) {
            $model->sendWebHook();
            \Yii::$app->session->setFlash('success', 'Талон ' . $model->ticket . ' успешно добавлен');
        }
        else {
            \Yii::$app->session->setFlash('error', 'Ошибка создания талона');
        }
        return $this->redirect(['ticket/index']);
    }
}
