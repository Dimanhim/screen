<?php

namespace common\components;

class ApiHelper
{

    public static function getDataFromApi($requestData)
    {
        if($requestData and isset($requestData['error']) and !$requestData['error'] and isset($requestData['data']) and $requestData['data']) {
            return $requestData['data'];
        }
        return false;
    }
}
