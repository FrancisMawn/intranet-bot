<?php

namespace csps\sapbot\controllers;

use Craft;
use csps\sapbot\Plugin;
use craft\web\Controller;

class BaseController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionSettings()
    {
        $settings = Plugin::$plugin->getSettings();

        return $this->renderTemplate('sapbot/settings', [
            'settings' => $settings,
        ]);
    }

}
