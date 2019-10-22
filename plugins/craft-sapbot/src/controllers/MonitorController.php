<?php

namespace csps\sapbot\controllers;

use Craft;
use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use craft\web\Controller;
use csps\sapbot\Plugin;
use csps\sapbot\records\UnmatchedQuery;

class MonitorController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        $variables = [
            'unmatched' => $this->getUnmatchedQueries(),
        ];

        return $this->renderTemplate('sapbot/monitor/index', $variables);
    }

    public function reorderQueries()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();
    }

    public function actionDelete()
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();
        $id = (int) $request->post('id');
        // @todo: Delete entry.

        return $this->asJson([
           'success' => true,
        ]);
    }

    public function actionConversation()
    {
        $request = Craft::$app->getRequest();

        // Fetch unmatched query record.
        $unmatchedQuery = $this->getUnmatchedQuery(
            (int) $request->post('entryId')
        );

        // Get conversation from sap API.
        $conversation = Plugin::$plugin->api->conversations()->get(
            $unmatchedQuery->conversationId
        );

        // Get the bot id to differentiate the messages when printing out the conversation.
        $botId = ArrayHelper::firstWhere($conversation->participants, 'isBot', true)->id;

        // Get the id of the unmatched query so that we can highlight it in the conversation.
        $unmatchedId = Json::decode($unmatchedQuery->payload)['nlp']['uuid'];

        // Render conversation using the template file.
        $html = $this->getView()->renderTemplate('sapbot/monitor/conversation', compact('conversation', 'botId', 'unmatchedId'));

        return $this->asJson([
            'html' => $html,
         ]);
    }

    // Protected Methods
    // =========================================================================

    protected function getUnmatchedQuery($entryId)
    {
        return UnmatchedQuery::find()
            ->select(['*'])
            ->where([
                'id' => $entryId,
            ])
            ->one();
    }

    protected function getUnmatchedQueries()
    {
        return UnmatchedQuery::find()
            ->select([
                'id',
                'source',
                'dateCreated',
                'dateUpdated',
                'uid',
            ])
            ->all();
    }

}
