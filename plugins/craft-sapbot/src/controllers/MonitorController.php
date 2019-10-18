<?php

namespace csps\sapbot\controllers;

use craft\web\Controller;
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

    // Protected Methods
    // =========================================================================

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
