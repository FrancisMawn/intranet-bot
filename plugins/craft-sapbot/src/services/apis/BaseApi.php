<?php

namespace csps\sapbot\services\apis;

use csps\sapbot\services\SapBotApi;

class BaseApi
{
    protected $client;
    protected $userSlug;
    protected $botSlug;
    protected $botVersion;

    public function __construct(SapBotApi $sapBotApi)
    {
        $this->client = $sapBotApi->getConnector();
        $this->userSlug = $sapBotApi->settings->get('userSlug');
        $this->botSlug = $sapBotApi->settings->get('botSlug');
        $this->botVersion = $sapBotApi->settings->get('botVersion');
    }
}
