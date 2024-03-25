<?php

namespace frontend\controllers;

use common\models\Api;
use common\models\Cabinet;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TabletController extends Controller
{
    public function actionIndex()
    {
        $this->layout = 'front';

        return $this->render('index', [

        ]);
    }
}

