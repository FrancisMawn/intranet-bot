<?php

namespace csps\sapbot\models;

use Craft;
use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;

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

    public function getDeveloperToken(): string
    {
        return Craft::parseEnv($this->developerToken);
    }

    public function getChannelTokenEn(): string
    {
        return Craft::parseEnv($this->channelTokenEn);
    }

    public function getChannelIdEn(): string
    {
        return Craft::parseEnv($this->channelIdEn);
    }

    public function getChannelTokenFr(): string
    {
        return Craft::parseEnv($this->channelTokenFr);
    }

    public function getChannelIdFr(): string
    {
        return Craft::parseEnv($this->channelIdFr);
    }

    public function getUserSlug(): string
    {
        return Craft::parseEnv($this->userSlug);
    }

    public function getBotSlug(): string
    {
        return Craft::parseEnv($this->botSlug);
    }

    public function getBotVersion(): string
    {
        return Craft::parseEnv($this->botVersion);
    }
}
