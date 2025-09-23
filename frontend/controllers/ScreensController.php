<?php

namespace frontend\controllers;

use common\models\Cabinet;
use yii\web\Controller;

class ScreensController extends Controller
{
    public $layout = 'front';

    public function actionIndex($room = null, $mode = null)
    {
        $cabinet = Cabinet::getByUniqueId($room);

        return $this->render('index', [
            'roomId' => $cabinet->unique_id ?? null,
            'roomNumber' => $cabinet->number ?? null,
            'mode' => $mode ?? null,
        ]);
    }
}
