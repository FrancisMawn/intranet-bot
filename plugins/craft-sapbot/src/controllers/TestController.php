<?php

namespace csps\sapbot\controllers;

use csps\sapbot\Plugin;

class TestController extends \craft\web\Controller
{
    public function actionResponse()
    {
        return $this->asJson(
            Plugin::getInstance()->api->bots()->getAll()
        );
    }
}
