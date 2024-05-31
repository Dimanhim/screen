<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $name;
    public $username;
    public $email;
    public $password;
    public $password_repeat;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['name', 'username', 'password', 'password_repeat'], 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            [['name', 'username'], 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            //['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 8, 'message' => 'Пароль должен содержать минимум 8 символов'],
            ['password_repeat', 'passwordRepeatValidator']
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function passwordRepeatValidator($attribute, $params)
    {
        if($this->password != $this->password_repeat) {
            $this->addError($attribute, 'Пароли не совпадают');
        }
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => 'Имя',
            'username' => 'Логин',
            'password' => 'Пароль',
            'password_repeat' => 'Пароль еще раз',
        ]);
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->name = $this->name;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->password_repeat = $this->password_repeat;
        $user->is_admin = 1;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if($user->save()) {
            return $user;
        }
        return false;
    }
}
