<?php

namespace backend\controllers;

use common\components\AccessesComponent;
use common\models\Cabinet;
use common\models\User;
use himiklab\thumbnail\EasyThumbnail;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use himiklab\sortablegrid\SortableGridAction;

/**
 1. переписываем behavours
 2. удаляем actionDelete
 3. удаляем findModel
*/
class BaseController extends Controller
{
    public $_errors = [];
    public $_data = [
        'error' => 0,
        'message' => null,
        'data' => [],
    ];

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if(!$user = User::find()->exists()) {
            if($action->id != 'signup') {
                return $this->redirect(['/site/signup']);
            }
        }
        elseif($action->id == 'signup') {
            return $this->redirect(['/cabinet/index']);
        }
        return parent::beforeAction($action);
    }
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),  [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'sort' => [
                'class' => SortableGridAction::className(),
                'modelName' => $this->behaviors()['className'],
            ],
        ];
    }

    /**
     * Deletes an existing ClinicType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->deleted = 1;
        if($model->save()) {
            Yii::$app->session->setFlash('success', 'Запись удалена успешно');
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the ClinicType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ClinicType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if(array_key_exists('className', $this->behaviors()) && ($model = $this->getModel())) {
            if(($findModel = $model::findOne(['id' => $id, 'is_active' => 1, 'deleted' => null])) !== null) {
                return $findModel;
            }
        }
        throw new NotFoundHttpException('Запрошенная страница не существует');
    }

    public function getModel()
    {
        $behaviors = $this->behaviors();
        if(array_key_exists('className', $this->behaviors())) {
            return $behaviors['className'];
        }
        return false;
    }

    public function _hasErrors()
    {
        return !empty($this->_errors);
    }

    public function _addError($message)
    {
        if($message) {
            $this->_errors[] = $message;
        }
    }

    public function _errorSummary()
    {
        if($this->_errors) return implode(' ', $this->_errors);
        return false;
    }


}
