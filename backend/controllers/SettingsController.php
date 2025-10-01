<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Setting;
use backend\models\SettingForm;

class SettingsController extends Controller
{
    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model = new SettingForm();
        $settings = Setting::find()->indexBy('key')->all();

        $model->setAttributesFromSettings($settings);

        if ($model->load(Yii::$app->request->post()) && $model->saveSettings()) {
            Yii::$app->session->setFlash('success', 'Настройки сохранены.');
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
