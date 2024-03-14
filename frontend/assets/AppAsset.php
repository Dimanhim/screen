<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        $this->css = static::getCss();
        $this->js = static::getJs();
    }

    public static function getCss()
    {
        return [
            '/design/css/styles.min.css?v='.mt_rand(1000,10000),
            '/css/main.css?v='.mt_rand(1000,10000),
        ];
    }

    public static function getJs()
    {
        return [
            'js/main.js?v='.mt_rand(1000,10000)
        ];
    }
}
