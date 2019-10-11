<?php

namespace csps\sapbot\services\apis;

class BotApi extends BaseApi
{
    protected function baseUrl()
    {
        return "core/v1/users/{$this->userSlug}";
    }

    public function getAll()
    {
        return $this->client->get("{$this->baseUrl()}/bots");
    }

    public function get(string $botSlug = null)
    {
        $botSlug = $botSlug ?? $this->botSlug;
        return $this->client->get("{$this->baseUrl()}/bots/$botSlug");
    }
}
