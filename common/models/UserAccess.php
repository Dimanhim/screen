<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_accesses".
 *
 * @property int $id
 * @property string $unique_id
 * @property int|null $user_id
 * @property int|null $access_id
 * @property int|null $is_active
 * @property int|null $deleted
 * @property int|null $position
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class UserAccess extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_accesses}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['user_id', 'clinic_id'], 'integer'],
            [['access_type'], 'string', 'max' => 255],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'user_id' => 'User ID',
            'clinic_id' => 'Clinic ID',
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
