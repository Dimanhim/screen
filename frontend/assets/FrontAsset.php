<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class FrontAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    private $directoryPath = 'screens';
    private $cssDir;
    private $jsDir;

    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        $this->cssDir = Yii::getAlias('@webroot').'/'.$this->directoryPath.'/css';
        $this->jsDir = Yii::getAlias('@webroot').'/'.$this->directoryPath.'/js';
        $this->setFiles();
    }

    public function setFiles()
    {
        $this->css = $this->getCss();
        $this->js = $this->getJs();
        return;
        $this->css = $this->getCssFile();
        $this->js = $this->getJsFile();
    }

    public function getCss()
    {
        return [
            '/'.$this->directoryPath.'/css/main.css?v='.mt_rand(1000,10000),
        ];
    }

    public function getJs()
    {
        return [
            '/'.$this->directoryPath.'/js/alpineDev.min.js',
            //'/'.$this->directoryPath.'/js/client.js?v='.mt_rand(1000,10000),
        ];
    }

    public function getCssFile()
    {
        $css = [];
        $css[] = $this->latestFilterStyle();
        return $css;
    }

    public function getJsFile()
    {
        $js = [];
        $js[] = $this->latestFilterScript();
        $js[] = $this->latestFilterVendorScript();
        return $js;
    }

    private function latestFilterStyle() {
        $dir = scandir($this->cssDir);
        $app = [];
        foreach ($dir as $file) {
            if (preg_match('/css/', $file)) {
                $app[] = $this->directoryPath.'/css/'.$file;
            }
        }
        $newestApp = array_reduce($app, function ($a, $b) {
            if (preg_match('/css/', $a) && filemtime(Yii::getAlias('@webroot').'/'.$a) > filemtime(Yii::getAlias('@webroot').'/'.$b)) {
                return $a;
            }
            return $b;
        });
        return $newestApp;
    }

    private function latestFilterScript() {
        $dir = scandir($this->jsDir);
        $app = [];
        foreach ($dir as $file) {
            if (preg_match('/app/', $file)) {
                $app[] = $this->directoryPath.'/js/'.$file;
            }
        }
        $newestApp = array_reduce($app, function ($a, $b) {
            if (preg_match('/app/', $a) && filemtime(Yii::getAlias('@webroot').'/'.$a) > filemtime(Yii::getAlias('@webroot').'/'.$b)) {
                return $a;
            }
            return $b;
        });
        return $newestApp;
    }
    private function latestFilterVendorScript() {
        $dir = scandir($this->jsDir);
        $vendorJs = [];
        foreach ($dir as $file) {
            if (preg_match('/chunk-vendors/', $file)) {
                $vendorJs[] = $this->directoryPath.'/js/'.$file;
            }
        }
        $newestVendorJs = array_reduce($vendorJs, function ($a, $b) {
            if (preg_match('/chunk-vendors/', $a) && filemtime(Yii::getAlias('@webroot').'/'.$a) > filemtime(Yii::getAlias('@webroot').'/'.$b)) {
                return $a;
            }
            return $b;
        });
        return $newestVendorJs;
    }


}
