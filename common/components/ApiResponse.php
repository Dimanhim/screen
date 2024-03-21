<?php

namespace common\models;

use Yii;

class ApiResponse
{
    public $result = [
        'error' => 0,
        'error_message' => null,
        'message' => null,
        'data' => [],
    ];














    public function hasErrors()
    {
        return $this->result['error'];
    }
    private function addError($errorMessage)
    {
        $this->result['error'] = 1;
        $this->result['error_message'] = $errorMessage;
        $this->result['data'] = [];
    }
}
