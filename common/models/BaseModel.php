<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use himiklab\sortablegrid\SortableGridBehavior;

class BaseModel extends ActiveRecord
{
    public $_today_start;
    public $_today_end;

    public function init()
    {
        $this->_today_start = date('d.m.Y') . ' 00:00';
        $this->_today_end   = date('d.m.Y') . ' 23:59';
        parent::init();
    }
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['unique_id', 'is_active', 'deleted', 'position', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'position'
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'unique_id' => 'Уникальный ID',
            'is_active' => 'Активность',
            'deleted' => 'Удален',
            'position' => 'Сортировка',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord) {
            $this->unique_id = uniqid();
            $this->is_active = 1;
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->session->setFlash('success', 'Изменения успешно сохранены');
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return mixed
     */
    public static function findModels()
    {
        return self::className()::find()->where(['is', 'deleted', null])->andWhere(['is_active' => 1])->orderBy(['position' => 'SORT ASC']);
    }

    public function printErrorSummary()
    {
        $errorMessages = [];
        if($this->errors) {
            foreach($this->errors as $attributeName => $errorValues) {
                if($errorValues) {
                    foreach($errorValues as $errorValue) {
                        $errorMessages[] = $errorValue;
                    }
                }
            }
        }
        return $errorMessages ? implode(' ', $errorMessages) : false;
    }
}
