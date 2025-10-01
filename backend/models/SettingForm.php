<?php

namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use common\models\Setting;

class SettingForm extends Model
{
    public $app_name = 'Сервис подписи';
    public $rnova_api_url = 'https://app.rnova.org/api/public/';
    public $rnova_api_key;
    public $rnova_webhook_key;
    public $socket_host;
    public $socket_port;
    public $socket_url;

    public function rules()
    {
        return [
            [['app_name', 'rnova_api_url', 'rnova_api_key'], 'required'],
            [['app_name', 'rnova_api_url', 'rnova_api_key', 'rnova_webhook_key', 'socket_host', 'socket_port', 'socket_url'], 'string'],
        ];
    }

    public function setAttributesFromSettings($settings)
    {
        foreach ($settings as $key => $setting) {
            if (property_exists($this, $key)) {
                $this->$key = $setting->value;
            }
        }
    }

    public function saveSettings()
    {
        $settings = [
            'app_name' => $this->app_name,
            'rnova_api_url' => $this->rnova_api_url,
            'rnova_api_key' => $this->rnova_api_key,
            'rnova_webhook_key' => $this->rnova_webhook_key,
            'socket_host' => $this->socket_host,
            'socket_port' => $this->socket_port,
            'socket_url' => $this->socket_url,
        ];

        foreach ($settings as $key => $value) {
            $setting = Setting::findOne(['key' => $key]) ?? new Setting(['key' => $key]);
            $setting->value = (string) $value;

            $setting->save();
        }

        return true;
    }
}
