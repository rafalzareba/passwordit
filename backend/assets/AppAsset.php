<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\bootstrap5\BootstrapAsset;
use yii\web\YiiAsset;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        ['scss/main.css', 'rel' => 'stylesheet preload prefetch', 'as' => 'style', 'crossorigin' => '']
    ];
    public $js = [
        [ 'js/vendor.js', 'rel' => 'script preload prefetch', 'as' => 'script', 'crossorigin' => ''],
    ];
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}
