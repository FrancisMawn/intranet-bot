<?php

namespace csps\sapbot\web\assets;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class MonitorAssets extends AssetBundle
{
    public function init()
    {
        parent::init();

        $this->sourcePath = '@csps/sapbot/resources/';

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/monitor.js',
        ];

        $this->css = [
            'css/monitor.css',
        ];
    }
}
