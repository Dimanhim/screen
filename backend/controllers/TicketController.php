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

        // Удалить
        $model->clinic_id = 21;
        $model->mis_id = '110 Невролог';

        $cabinets = Cabinet::findModels()->all();

        return $this->render('index', [
            'model' => $model,
            'cabinets' => $cabinets,
            'list' => [],
        ]);
    }
}
