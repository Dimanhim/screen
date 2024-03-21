<?php

namespace common\components;

use yii\base\Model;

class RnovaApi extends Model
{
    public $result = [
        'error' => 0,
        'message' => null,
        'data' => [],
    ];

    protected $request_url = null;
    protected $api_key = null;

    protected $time_start;
    protected $time_end;
    protected $time_now;

    public function init()
    {
        $this->request_url = $this->request_url ?? $_ENV['MIS_REQUEST_API_URL'];
        $this->api_key = $this->api_key ?? $_ENV['MIS_API_KEY'];
        $this->time_start = date('d.m.Y').' 00:00';
        $this->time_end = date('d.m.Y').' 23:59';
        $this->time_now = date('d.m.Y H:i');
        return parent::init();
    }

    public function getTimeStart()
    {
        return $this->time_start;
    }
    public function getTimeEnd()
    {
        return $this->time_end;
    }
    public function getTimeNow()
    {
        return $this->time_now;
    }
    public function getDate()
    {
        return date('d.m.Y', strtotime($this->time_start));
    }

    public function getRequest($method, $params = [], $version = null)
    {
        $data = $this->apiRequest($method, $params, $version);
        return $data;
    }

    protected function setError($message = null)
    {
        $this->result['error'] = 1;
        $this->result['message'] = $message;
        $this->result['data'] = [];
    }


    protected function getResponse($json)
    {
        if($json and ($data = json_decode($json, true)) and isset($data['data']) and isset($data['error'])) {
            $this->result['error'] = $data['error'];
            $this->result['data'] = $data['data'];
        }
        return $this->result;
    }

    private function getFullUrl($method, $version)
    {
        $url = $this->request_url;
        if($version) {
            $url .= $version .'/';
        }
        $url .= $method . '?api_key='.$this->api_key;
        return $url;
    }

    private function apiRequest($method, $params = [], $version)
    {
        $url = $this->getFullUrl($method, $version);
        $curl = curl_init();
        $build_query = http_build_query($params);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $build_query,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic a3V0c2FldmEuZGFyaWFfYXBpMS5nbWFpbC5jb206Rk03WDJGOVJTWFRFV0w3NQ==',
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: PHPSESSID=eglk0r28e3ccle8gg03ob9igs2'
            ),
        ));

        $response = curl_exec($curl);

        $info = curl_getinfo($curl);
        if($info['http_code'] != 200) {
            file_put_contents('curl-logs.txt', date('d.m.Y H:i:s').' - '.print_r($info, true)."\n", FILE_APPEND);
        }
        curl_close($curl);
        return $this->getResponse($response);
    }
}
