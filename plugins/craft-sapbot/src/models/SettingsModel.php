<?php

namespace csps\sapbot\models;

use Craft;
use craft\base\Model;
use craft\helpers\StringHelper;

class SettingsModel extends Model
{
    // SAP Bot configurations.
    public $enabled = true;
    public $apiURL = '';
    public $developerToken = '';
    public $channelTokenEn = '';
    public $channelIdEn = '';
    public $channelTokenFr = '';
    public $channelIdFr = '';
    public $userSlug = '';
    public $botSlug = '';
    public $botVersion = '';

    // Weather integration.
    public $weatherApiURL = '';
    public $weatherApiToken = '';

    public function get($config)
    {
        // Handle environmental configs.
        if (StringHelper::startsWith($this->{$config}, '$SAPBOT_')) {
            return Craft::parseEnv($this->{$config});
        }

        return $this->{$config};
    }

}
