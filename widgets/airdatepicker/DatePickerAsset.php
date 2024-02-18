<?php

namespace app\widgets\airdatepicker;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DatePickerAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/asset';

    public $css = [
        'css/datepicker.css',
    ];

    public $js = [
        'js/datepicker.min.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}