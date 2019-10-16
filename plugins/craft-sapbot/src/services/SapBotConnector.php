<?php

namespace csps\sapbot\services;

use Craft;

class SapBotConnector
{
    protected $apiURL;
    protected $developerToken;
    protected $client;

    public function __construct(SapBotApi $sapBotApi)
    {
        $this->apiURL = $sapBotApi->settings->apiURL;
        $this->developerToken = $sapBotApi->settings->developerToken;
        $this->client = $this->createClient();
    }

    protected function createClient()
    {
        return Craft::createGuzzleClient([
            'headers' => [
                'Authorization' => "Token {$this->developerToken}",
                'Content-Type'  => 'application/json',
            ]
        ]);
    }

    public function request(string $method, string $route, array $data = [])
    {
        $response = $this->client->request($method, "$this->apiURL/$route", $data);
        return json_decode($response->getBody())->results;
    }

    public function get(string $url, array $query = [])
    {
        return $this->request('GET', $url, ['query' => $query]);
    }

    public function post(string $url, array $data = [])
    {
        // Handle multipart/form-data post.
        if (isset($data['multipart'])) {
            return $this->request('POST', $url, $data);
        }
        // Default to json format.
        return $this->request('POST', $url, ['json' => $data]);
    }

    public function put(string $url, array $data = [])
    {
        return $this->request('PUT', $url, ['json' => $data]);
    }

    public function delete(string $url)
    {
        return $this->request('DELETE', $url);
    }
}
