<?php

namespace common\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\Response;

class ApiBaseController extends Controller
{
    protected $errors = [];
    protected $data = [
        'error' => 0,
        'data' => [],
    ];
    protected $allowedActions = ['get-room', 'get-appointments', 'get-user-url'];

    /**
     * @param $action
     * @return bool
     * @throws NotAcceptableHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        if(!in_array($action->id, $this->allowedActions)) $this->checkApiKey();
        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'corsFilter' => [
                'class' => Cors::class,
            ],
        ];
    }

    /**
     * @return mixed
     */
    protected function checkApiKey()
    {
        if(\Yii::$app->settings->getParam('rnova_webhook_key') && \Yii::$app->settings->getParam('rnova_webhook_key') !== Yii::$app->request->get('key')) {
            $this->addError(403, 'Неверный ключ запроса');
            return $this->response();
        }
    }

    public function addData($data)
    {
        $this->data['data'] = $data;
    }

    protected function getErrors()
    {
        return $this->errors;
    }

    protected function hasErrors()
    {
        return !empty($this->errors);
    }

    protected function addError($code = 404, $message)
    {
        if($message) {
            $this->errors[] = ['code' => $code, 'message' => $message];
        }
    }

    /**
     * @return bool|string
     */
    protected function errorSummary()
    {
        if($this->errors) {
            return implode(' ', array_map(function($n) {
                return $n['message'];
            }, $this->errors));
        }
        return false;
    }

    /**
     * @return |null
     */
    protected function getFirstErrorCode()
    {
        return $this->errors[0]['code'] ?? null;
    }

    /**
     * @return |null
     */
    protected function getFirstErrorMessage()
    {
        return $this->errors[0]['message'] ?? null;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function response($data = [])
    {
        if(!$this->hasErrors()) {
            $this->data['data'] = $data;
        }
        else {
            $this->data['error'] = 1;
            $this->data['data']['desc'] = $this->getFirstErrorMessage();
            $this->data['data']['code'] = $this->getFirstErrorCode();
            \Yii::$app->infoLog->add($this->getFirstErrorMessage(), $this->getFirstErrorCode(), 'api-errors.txt');
        }

        $this->response->data = $this->data;
        return $this->response->data;
    }
}

