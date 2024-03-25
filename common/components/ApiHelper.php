<?php

namespace common\components;

use Yii;
use common\models\Cabinet;
use common\components\Api;

class ApiHelper
{

    public static function getDataFromApi($requestData)
    {
        if($requestData and isset($requestData['error']) and !$requestData['error'] and isset($requestData['data']) and $requestData['data']) {
            return $requestData['data'];
        }
        return false;
    }

    public static function getScheduleData($data)
    {
        if($scheduleData = self::getDataFromApi($data)) {
            foreach($scheduleData as $scheduleItem) {
                return $scheduleItem;
            }
        }
        return false;
    }

    public static function testApi()
    {
        $data = [
            'user' => [
                [
                    'id' => 39,
                    'avatar' => '',
                    'name' => 'Маркина Татьяна Петровна',
                    'role_titles' => 'Медицинская сестра',
                    'profession_titles' => 'Офтальмология'
                ],
            ],
            'appointments' => [
                2 => [
                    [
                        'time_start' => '25.03.2024 15:00',
                        'time_end' => '25.03.2024 15:20',
                        'patient_id' => 12532,
                        'patient_name' => 'Иванов Иван Иванович',
                        'ticket' => 'Л001',
                    ],
                    [
                        'time_start' => '25.03.2024 15:30',
                        'time_end' => '25.03.2024 15:45',
                        'patient_id' => 11111,
                        'patient_name' => 'Петров Петр Петрович',
                        'ticket' => 'Л002',
                    ],
                    [
                        'time_start' => '25.03.2024 16:00',
                        'time_end' => '25.03.2024 16:30',
                        'patient_id' => 11111,
                        'patient_name' => 'Петров Петр Петрович',
                        'ticket' => 'Л003',

                    ],
                ],
                3 => [
                    [
                        'time_start' => '25.03.2024 17:00',
                        'time_end' => '25.03.2024 17:45',
                        'patient_id' => 11111,
                        'patient_name' => 'Петров Петр Петрович',
                        'ticket' => 'Л004',
                    ],
                    [
                        'time_start' => '25.03.2024 18:00',
                        'time_end' => '25.03.2024 18:30',
                        'patient_id' => 11111,
                        'patient_name' => 'Петров Петр Петрович',
                        'ticket' => 'Л005',
                    ],
                ],
            ],

        ];

        return $data;
    }

}
