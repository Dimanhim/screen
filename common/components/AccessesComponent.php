<?php

namespace common\components;

use common\models\Building;
use common\models\Cabinet;
use common\models\User;
use common\models\UserAccess;
use Yii;
use yii\base\Component;

class AccessesComponent extends Component
{
    const TYPE_USERS    = 'users';
    const TYPE_CLINIC   = 'clinic';
    const TYPE_CABINET  = 'cabinet';
    const TYPE_TICKETS  = 'ticket';

    public $_accesses = [

    ];

    private $_clinics = [];

    /**
     *
     */
    public function init()
    {
        $this->setClinics();
        return parent::init();
    }

    /**
     *
     */
    public function setClinics()
    {
        if($clinics = Yii::$app->api->getClinics()) {
            $this->_clinics = ApiHelper::getDataFromApi($clinics);
        }
    }

    /**
     * @return array
     */
    public function getClinics()
    {
        return $this->_clinics;
    }

    /**
     * @return array
     */
    public static function typeNames()
    {
        return [
            self::TYPE_USERS     => 'Пользователи',
            self::TYPE_CLINIC    => 'Корпуса',
            self::TYPE_CABINET   => 'Кабинеты',
            self::TYPE_TICKETS   => 'Талоны',
        ];
    }

    /**
     * @param $type
     * @return bool|mixed
     */
    public static function typeName($type)
    {
        $typeNames = self::typeNames();
        if(array_key_exists($type, $typeNames)) return $typeNames[$type];
        return false;
    }

    /**
     * @param $user_id
     * @return array
     */
    public function getAccessesForUser($user_id = false)
    {
        if($user_id === false) $user_id = Yii::$app->user->id;
        $accesses = $this->getAccesses();
        if(($user = User::findOne($user_id)) and ($userAccesses = $user->accessesList)) {
            foreach($userAccesses as $userAccessType => $userAccessIds) {
                if(is_array($userAccessIds) and $userAccessIds) {
                    foreach($userAccessIds as $userAccessId) {
                        if(array_key_exists($userAccessId, $accesses[$userAccessType]['access_values'])) {
                            $accesses[$userAccessType]['access_values'][$userAccessId]['checked'] = 1;
                        }
                    }
                }
                else {
                    $accesses[$userAccessType]['checked'] = 1;
                }
            }
        }
        return $accesses;
    }

    /**
     * @param $access_type
     * @param null $clinic_id
     * @param null $user_id
     * @param null $general_access
     * @return bool
     */
    public function hasAccess($access_type, $clinic_id = null, $user_id = null, $general_access = null)
    {
        if(!$user_id) $user_id = Yii::$app->user->id;
        $access = $general_access
            ?
            UserAccess::find()->where(['user_id' => $user_id, 'access_type' => $access_type])->exists()
            :
            UserAccess::find()->where(['user_id' => $user_id, 'access_type' => $access_type, 'clinic_id' => $clinic_id])->exists();
        return $access;
    }

    /**
     * @return bool|mixed
     */
    public function getClinicData()
    {
        if($clinics = Yii::$app->api->getClinics()) {
            return ApiHelper::getDataFromApi($clinics);
        }
        return false;
    }


    /**
     * @return array
     */
    public function getAccesses()
    {
        // это дефолтный, нужно заполнить значениями юзера
        $clinicData = [];
        if($clinics = self::getClinicData()) {
            foreach($clinics as $clinic) {
                $clinicData[$clinic['id']] = [
                    'id' => $clinic['id'],
                    'name' => $clinic['title'],
                    'checked' => false
                ];
            }
        }
        return [
            self::TYPE_USERS => [
                'access_type' => self::TYPE_USERS,
                'access_name' => $this->typeName(self::TYPE_USERS),
                'checked' => false,
                'access_values' => [],
            ],
            /*self::TYPE_CLINIC => [
                'access_type' => self::TYPE_CLINIC,
                'access_name' => $this->typeName(self::TYPE_CLINIC),
                'checked' => false,
                'access_values' => [],
            ],*/
            self::TYPE_CABINET => [
                'access_type' => self::TYPE_CABINET,
                'access_name' => $this->typeName(self::TYPE_CABINET),
                'checked' => false,
                'access_values' => $clinicData,
            ],
            self::TYPE_TICKETS => [
                'access_type' => self::TYPE_TICKETS,
                'access_name' => $this->typeName(self::TYPE_TICKETS),
                'checked' => false,
                'access_values' => $clinicData,
            ],
        ];
    }
}
