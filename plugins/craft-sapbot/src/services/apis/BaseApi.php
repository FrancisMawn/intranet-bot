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
        $this->userSlug = $sapBotApi->settings->userSlug;
        $this->botSlug = $sapBotApi->settings->botSlug;
        $this->botVersion = $sapBotApi->settings->botVersion;
    }
}
