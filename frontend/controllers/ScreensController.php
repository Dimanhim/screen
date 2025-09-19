<?php

namespace frontend\controllers;

use yii\web\Controller;

class ScreensController extends Controller
{
    public $layout = 'front';

    public function actionIndex($roomId = null, $roomNumber = null, $mode = null)
    {
        return $this->render('index', [
            'roomId' => $roomId,
            'roomNumber' => $roomNumber,
            'mode' => $mode,
        ]);
    }
}
