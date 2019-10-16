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
        $answer = 'I\'m sorry, I couldn\'t find your location. Please try again with a broader region.';

        try {
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

            $weather = $body->weather[0]->main;
            $description = $body->weather[0]->description;
            $temperature = round($body->main->temp);
            $city = $location['city'];

            /*
            let mainWeather = body.weather[0].main;
            if (mainWeather === 'Clear') {
                mainWeather = 'Sun';
            }
            let mainDesc = body.weather[0].description;

            // reset location memory
            delete req.body.conversation.memory.location;

            if (language == 'fr') {
                replyText = `La prévision pour ${location.formatted} aujourd'hui est **${mainDesc.toLowerCase()}**, avec une température ${weatherAdjFR(body.main.temp)} de **${Math.round(body.main.temp)} °C**! (${Math.round(body.main.temp_max)}/${Math.round(body.main.temp_min)}) ${weatherEmoji(mainWeather)}`;
            } else {
                replyText = `The forecast for ${location.formatted} today is **${mainDesc.toLowerCase()}**, with a ${weatherAdjEN(body.main.temp)} temperature of **${Math.round(body.main.temp)}°C**! (${Math.round(body.main.temp_max)}/${Math.round(body.main.temp_min)}) ${weatherEmoji(mainWeather)}`;
            }
            */
            $answer = "The forecast for $city today is $description, with a temperature of {$temperature}°C!";
            return $answer;


        } catch (ClientException $e) {
            return $answer;
        }
    }
}
