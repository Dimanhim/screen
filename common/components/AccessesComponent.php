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
    const TYPE_BUILDING = 'building';
    const TYPE_CABINET  = 'cabinet';
    const TYPE_TICKETS  = 'ticket';

    public $type;
    public $_accesses = [

    ];

    public function init()
    {
        return parent::init();
    }

    public static function typeNames()
    {
        return [
            self::TYPE_USERS     => 'Пользователи',
            self::TYPE_BUILDING  => 'Корпуса',
            self::TYPE_CABINET   => 'Кабинеты',
            self::TYPE_TICKETS   => 'Талоны',
        ];
    }

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
    public function getAccessesForUser($user_id = null)
    {
        if(!$user_id) $user_id = Yii::$app->user->id;
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

    public function hasAccess($access_type, $building_id = null, $user_id = null, $general_access = null)
    {
        if(!$user_id) $user_id = Yii::$app->user->id;
        return $general_access
            ?
            UserAccess::find()->where(['user_id' => $user_id, 'access_type' => $access_type])->exists()
            :
            UserAccess::find()->where(['user_id' => $user_id, 'access_type' => $access_type, 'building_id' => $building_id])->exists();
    }

    /**
     * Для примера
     */
    public function getAccesses()
    {
        // это дефолтный, нужно заполнить значениями юзера
        $buildingData = [];
        if($buildings = Building::findModels()->all()) {
            foreach($buildings as $building) {
                $buildingData[$building->id] = [
                    'id' => $building->id,
                    'name' => $building->name,
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
            self::TYPE_BUILDING => [
                'access_type' => self::TYPE_BUILDING,
                'access_name' => $this->typeName(self::TYPE_BUILDING),
                'checked' => false,
                'access_values' => [],
            ],
            self::TYPE_CABINET => [
                'access_type' => self::TYPE_CABINET,
                'access_name' => $this->typeName(self::TYPE_CABINET),
                'checked' => false,
                'access_values' => $buildingData,
            ],
            self::TYPE_TICKETS => [
                'access_type' => self::TYPE_TICKETS,
                'access_name' => $this->typeName(self::TYPE_TICKETS),
                'checked' => false,
                'access_values' => $buildingData,
            ],
        ];
    }


}
