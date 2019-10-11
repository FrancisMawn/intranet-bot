<?php

namespace csps\sapbot\controllers;

use Craft;

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
        $answer = 'Sorry, I could not find any information on the subject...';

        if ($query = $request->getBodyParam('nlp')['entities']['query'][0]['value'] ?? null) {
            $entry = \craft\elements\Entry::find()
                ->section('sapBotQuery')
                ->search($query)
                ->orderBy('score')
                ->one();
            // If too many results, return a too generic answer please clarify.
            $answer = $entry->sapBotQueryAnswer ?? $answer;
        };

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

    public function actionQueryNotFound()
    {
        $request = Craft::$app->getRequest();
        $userInput = $request->getBodyParam('nlp')['source'];
        $conversationId = $request->getBodyParam('conversation')['id'];
        // $answer = 'Let me help you, what exactly do you need?';

        Craft::trace($userInput);
        Craft::trace($conversationId);

        return $this->asJson([
            'replies' => []
        ]);
    }

}
