<?php

namespace csps\sapbot\services\apis;

class ConversationApi extends BaseApi
{
    protected function baseUrl()
    {
        return "connect/v1";
    }

    public function getAll()
    {
        return $this->client->get("{$this->baseUrl()}/conversations");
    }

    public function get(string $conversationId)
    {
        return $this->client->get("{$this->baseUrl()}/conversations/$conversationId");
    }
}
