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

        if(!\Yii::$app->request->get('id')) {
            throw new NotFoundHttpException('Запрошенная страница не существует');
        }

        return $this->render('index', [

        ]);
    }

    public function actionContentPage()
    {
        echo "<pre>";
        print_r(\Yii::$app->request->get('id'));
        echo "</pre>";
        exit;
    }
}

