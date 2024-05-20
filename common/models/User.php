<?php

namespace common\models;

use common\components\AccessesComponent;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{

    const SCENARIO_CREATE = 'create';

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $sections_accesses;
    public $password;
    public $password_repeat;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->handleAccesses();
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     *
     */
    public function afterFind()
    {
        if($this->password) {
            $this->password_repeat = $this->password;
        }
        return parent::afterFind();
    }

    public function beforeValidate()
    {
        if ($this->password) {
            $this->setPassword($this->password);
            if(!$this->auth_key) {
                $this->generateAuthKey();
            }
        }
        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'username'], 'required', 'message' => 'Необходимо заполнить поле'],
            [['password', 'password_repeat'], 'required', 'on' => self::SCENARIO_CREATE, 'message' => 'Необходимо заполнить поле'],
            [['username'], 'unique', 'message' => 'Пользователь с таким логином уже зарегистрирован'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            //['auth_key', 'default', 'value' => ''],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['is_admin'], 'integer'],
            [['sections_accesses', 'password', 'password_repeat'], 'safe'],
            ['password_repeat', 'passwordRepeatValidator']
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Имя',
            'username' => 'Логин',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль еще раз',
            'sections_accesses' => 'Доступы к разделам',
        ]);
    }

    /**
     *
     */
    public function handleAccesses()
    {
        UserAccess::deleteAll(['user_id' => $this->id]);
        if($this->sections_accesses) {
            foreach($this->sections_accesses as $access_type => $access_values) {
                if(is_array($access_values)) {
                    foreach($access_values as $access_value) {
                        $userAccess = new UserAccess();
                        $userAccess->user_id = $this->id;
                        $userAccess->access_type = $access_type;
                        $userAccess->building_id = $access_value;
                        $userAccess->save();
                    }
                }
                else {
                    $userAccess = new UserAccess();
                    $userAccess->user_id = $this->id;
                    $userAccess->access_type = $access_type;
                    $userAccess->save();
                }
            }
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function passwordRepeatValidator($attribute, $params)
    {
        if($this->password !== $this->password_repeat) {
            $this->addError($attribute, 'Пароли не совпадают');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccesses()
    {
        return $this->hasMany(UserAccess::className(), ['user_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getAccessesList()
    {
        $list = [];
        if($this->accesses) {
            foreach($this->accesses as $access) {
                if($access->building_id) {
                    $list[$access->access_type][] = $access->building_id;
                }
                else {
                    $list[$access->access_type] = $access->building_id;
                }
            }
        }
        return $list;
    }

    /**
     * @return string
     */
    public function getGeneralAccessesHtml()
    {
        $str = '';
        if($list = $this->accessesList) {
            $str .= '<ul class="accesses-type-list">';
            foreach($list as $accessName => $accessValues) {
                $str .= '<li>'.AccessesComponent::typeName($accessName).'</li>';
            }
            $str .= '</ul>';
        }
        return $str;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function isAdmin()
    {
        if($user = self::findIdentity(Yii::$app->user->id)) {
            return $user->is_admin;
        }
        return false;
    }
}
