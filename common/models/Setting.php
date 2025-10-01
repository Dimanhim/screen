<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "_settings".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $key
 * @property string|null $value
 * @property int|null $available_for_api
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Setting extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['value'], 'string'],
            [['key'], 'string'],
        ]);
    }

    public function apiResponse() {
        return [$this->key => $this->value];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Ключ',
            'value' => 'Значение',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }

    public static function getValueByName($valueName = null)
    {
        if($model = Setting::findOne(['key' => $valueName])) {
            return $model->value;
        }
        return null;
    }
}
