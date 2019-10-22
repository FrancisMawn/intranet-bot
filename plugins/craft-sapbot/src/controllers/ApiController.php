<?php

namespace csps\sapbot\controllers;

use Craft;
use csps\sapbot\Plugin;
use csps\sapbot\records\UnmatchedQuery;

class ApiController extends \craft\web\Controller
{
    public $enableCsrfValidation = false;
    protected $allowAnonymous = true;

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
            ],
        ];
    }

    public function actionQuery()
    {
        // $this->requirePostRequest();

        $request = Craft::$app->getRequest();
        $answer = 'I\'m sorry, I couldn\'t find any information on the subject...';

        if ($query = $request->getBodyParam('nlp')['entities']['query'][0]['value'] ?? null) {
            $entry = \craft\elements\Entry::find()
                ->section('sapBotQuery')
                ->search($query)
                ->orderBy('score')
                ->one();
            // @todo: If too many results, return a too generic answer please clarify?
            $answer = $entry->sapBotAnswer ?? $answer;
        };

        return $this->asJson([
            'replies' => [
                [
                    'type'     => 'text',
                    'content'  => $answer,
                    'markdown' => true,
                ]
            ]
        ]);
    }

    public function actionQueryNotFound()
    {
        $request = Craft::$app->getRequest();

        $record = new UnmatchedQuery();
        $record->conversationId = $request->getBodyParam('conversation')['id'];
        $record->source = $request->getBodyParam('nlp')['source'];
        $record->payload = $request->getRawBody();
        $record->save();

        return $this->asJson([
            'replies' => []
        ]);
    }

    public function actionWeather()
    {
        $request = Craft::$app->getRequest();
        $answer = 'I don\'t have your location...';

        if ($location = $request->getBodyParam('conversation')['memory']['location'] ?? null) {
            $language = $request->getBodyParam('nlp')['language'] ?? null;
            $answer = Plugin::getInstance()->weather->getWeatherFor($location, $language);
        }

        return $this->asJson([
            'replies' => [
                [
                    'type' => 'text',
                    'content' => $answer,
                    'markdown' => true,
                ]
            ]
        ]);
    }

}
