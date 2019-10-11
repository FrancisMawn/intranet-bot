<?php

namespace csps\sapbot\services;

use csps\sapbot\Plugin;
use csps\sapbot\services\apis\BotApi;
use csps\sapbot\services\apis\ConversationApi;
use csps\sapbot\services\apis\SynonymApi;
use csps\sapbot\services\Connector;
use yii\base\Component;

class SapBotApi extends Component
{
    public $settings;

    public function init()
    {
        parent::init();

        $this->settings = Plugin::getInstance()->settings;
    }

    public function getConnector()
    {
        return new Connector($this);
    }

    public function bots()
    {
        return new BotApi($this);
    }

    public function conversations()
    {
        return new ConversationApi($this);
    }

    public function synonyms()
    {
        return new SynonymApi($this);
    }
}
