<?php

namespace csps\sapbot\controllers;

use Craft;
use craft\helpers\ArrayHelper;
use craft\web\Controller;
use csps\sapbot\Plugin;
use csps\sapbot\records\UnmatchedQuery;
use GuzzleHttp\Exception\GuzzleException;

class MonitorController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        return $this->renderTemplate('sapbot/monitor/index', [
            'unmatched' => $this->getUnmatchedQueries(),
        ]);
    }

    public function reorderQueries()
    {
        $this->requirePostRequest();
    }

    public function actionDelete()
    {
        $this->requirePostRequest();

        // Get the entry id from the request.
        $request = Craft::$app->getRequest();
        $id = (int) $request->post('id');

        // Delete the entry.
        Craft::$app->getDb()->createCommand()
            ->delete(UnmatchedQuery::tableName(), ['id' => $id])
            ->execute();

        return $this->asJson(['success' => true]);
    }

    public function actionConversation()
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();

        // Fetch unmatched query record.
        $unmatchedQuery = $this->getUnmatchedQuery(
            (int) $request->post('entryId')
        );

        try {
            // Get conversation from sap API.
            $conversation = Plugin::$plugin->api->conversations()->get(
                $unmatchedQuery->conversationId
            );
        } catch (GuzzleException $e) {
            return $this->asJson(['html' => "<code class='error'>{$e->getMessage()}</code>"]);
        }

        // Get the bot id to differentiate the messages when printing out the conversation.
        $botId = ArrayHelper::firstWhere($conversation->participants, 'isBot', true)->id;

        // Used to highlight the unmatched query within the conversation.
        $source = $unmatchedQuery->source;

        // Render conversation using the template file.
        $html = $this->getView()->renderTemplate('sapbot/monitor/conversation', compact(
            'conversation', 'botId', 'source'
        ));

        return $this->asJson(['html' => $html]);
    }

    // Protected Methods
    // =========================================================================

    protected function getUnmatchedQuery($entryId)
    {
        return UnmatchedQuery::find()
            ->select(['*'])
            ->where(['id' => $entryId])
            ->one();
    }

    protected function getUnmatchedQueries()
    {
        return UnmatchedQuery::find()
            ->select(['*'])
            ->all();
    }

}
