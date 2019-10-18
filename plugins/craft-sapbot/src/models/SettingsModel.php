<?php

namespace csps\sapbot\models;

use craft\base\Model;

class SettingsModel extends Model
{
    // @todo: Move some configurations to environmental settings.
    // https://docs.craftcms.com/v3/extend/environmental-settings.html

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
}
