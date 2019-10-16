<?php

namespace csps\sapbot\services;

use Craft;
use craft\base\Component;
use csps\sapbot\Plugin;
use GuzzleHttp\Exception\ClientException;

class Weather extends Component
{
    protected $client;
    protected $settings;

    public function init()
    {
        parent::init();

        $this->client = Craft::createGuzzleClient([
            'headers' => [
                'Content-Type'  => 'application/json',
            ],
        ]);

        $this->settings = Plugin::getInstance()->settings;
    }

    public function getWeatherFor($location, $language = 'en')
    {
        try {
            $answer = 'I\'m sorry, I couldn\'t find your location. Please try again with a broader region.';

            $response = $this->client->request('GET', "{$this->settings->weatherApiURL}/weather", [
                'query' => [
                    'appid'    => $this->settings->weatherApiToken,
                    'language' => $language,
                    'units'    => 'metric',
                    'lat'      => round($location['lat']),
                    'lon'      => round($location['lng']),
                ]
            ]);

            $body = json_decode($response->getBody());

            // No results from weather API.
            if (!$body || !isset($body->weather) || empty($body->weather)) {
                return $answer;
            }

            // Read weather from API response and return a human friendly message.
            $description = $body->weather[0]->description;
            $temperature = round($body->main->temp);
            $city = $location['city'];
            $answer = "The forecast for $city today is $description, with a temperature of {$temperature}°C!";

            return $answer;
        } catch (ClientException $e) {
            // Something went wrong with the request, return default answer.
            return 'I\'m sorry, I couldn\'t get the weather just now, maybe try later?';
        }
    }
}
