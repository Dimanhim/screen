<?php

namespace common\components;

use Yii;
use yii\helpers\Url;

class Helpers
{
    /**
     * @param $phone
     * @param bool $plus
     * @return mixed|string
     */
    public static function phoneFormat($phone, $signPlus = false)
    {
        if($phone) {
            $sym = ['-', '(', ')', '_', ' '];
            $replaced = ['','','','',''];
            if($signPlus) {
                $sym[] = '+';
                $replaced[] = '';
            }
            return str_replace($sym, $replaced, $phone);
        }
        return '';
    }


    public static function setPhoneFormat($phone)
    {
        $phone = self::phoneFormat($phone);
        $phoneBody = substr($phone, -10);
        return '+7'.$phoneBody;
    }

    public static function getSecondsInTime($time)
    {
        $seconds = 0;
        $arr = explode(':', $time);
        $seconds += $arr[0] * 60 * 60;
        $seconds += $arr[1] * 60;
        return $seconds;
    }
    public static function getTimeAsString($time)
    {
        if($time) {
            $hours = floor($time / 60 / 60);
            $diff = $time - $hours * 60 * 60;
            $minutes = floor($diff / 60);
            return str_pad($hours, 2, 0, STR_PAD_LEFT).':'.str_pad($minutes, 2, 0, STR_PAD_LEFT);
        }
        return 0;
    }

    public static function getFileInputOptions()
    {
        return [
            'options' => [
                'accept' => 'image/*',
                'multiple' => true
            ],
            'pluginOptions' => [
                'browseLabel' => 'Выбрать',
                //'showPreview' => false,
                //'showUpload' => false,
                //'showRemove' => false,
            ]
        ];
    }

    public static function getTimeFromDatetime($datetime)
    {
        $timestampFull = strtotime($datetime);
        $dateTimestamp = strtotime(date('d.m.Y', $timestampFull));
        if($timestampFull and $dateTimestamp) {
            return self::getTimeAsString($timestampFull - $dateTimestamp);
        }
        return null;
    }

    public static function dateTimeFormat($dateTime)
    {
        $timestamp = strtotime($dateTime);
        return date('d.m.Y H:i', $timestamp);
    }

    /**
     * @param $date_from
     * @param $date_to
     * @param $subject
     * @param bool $timestamp
     * @return bool
     */
    //subject 12:30 date_from 12:00 date_to 12:30
    public static function isDatesBetween($date_from, $date_to, $subject, $toTimestamp = false)
    {
        $dates = [
            'date_from' => $toTimestamp ? strtotime($date_from) : $date_from,
            'date_to' => $toTimestamp ? strtotime($date_to) : $date_to,
            'subject' => $toTimestamp ? strtotime($subject) : $subject,
        ];
        return $dates['subject'] >= $dates['date_from'] and $dates['subject'] < $dates['date_to'];
    }

    /**
     * @return bool|string
     */
    public static function getAbsoluteUrl()
    {
        $url = str_replace('admin-', '', Url::home(true));
        return substr($url, 0, -1);
    }
}
