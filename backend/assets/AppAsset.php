<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];

    public function init()
    {
        $this->css = static::getCss();
        $this->js = static::getJs();
    }

    public static function getCss()
    {
        return [
            'css/bootstrap-icons.css',
            'css/font-awesome.min.css',
            'css/site.css?v='.mt_rand(1000,10000),
        ];
    }

    public static function getJs()
    {
        return [
            //'js/bootstrap.min.js',
            'js/slugify.js',
            'js/functions.js?v='.mt_rand(1000,10000),
            'js/common.js?v='.mt_rand(1000,10000),
        ];
    }
}
