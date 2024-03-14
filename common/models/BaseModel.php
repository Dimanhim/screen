<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use himiklab\sortablegrid\SortableGridBehavior;

class BaseModel extends ActiveRecord
{
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
}
