<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "buildings".
 *
 * @property int $id
 * @property string $unique_id
 * @property string $name
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Building extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%buildings}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'required', 'message' => 'Необходимо заполнить поле'],
            [['name'], 'string', 'max' => 255],
            [['clinic_id'], 'integer'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Название',
            'clinic_id' => 'Клиника',
        ]);
    }

    /**
     * @param string $attributeTo
     * @return array
     */
    public static function getList($attributeTo = 'name')
    {
        return ArrayHelper::map(self::findModels()->asArray()->all(), 'id', $attributeTo);
    }
}
