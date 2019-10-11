<?php

namespace csps\sapbot\models;

use craft\base\Model;

class SettingsModel extends Model
{
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
}
