<?php

namespace csps\sapbot;

use Craft;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Elements;
use craft\web\UrlManager;
use csps\sapbot\models\SettingsModel;
use csps\sapbot\services\SapBotApi;
use csps\sapbot\services\TagManager;
use yii\base\Event;

class Plugin extends \craft\base\Plugin
{
    // Public Properties
    // =========================================================================

    public $hasCpSection = true;
    public $hasCpSettings = true;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        // Define plugin components.
        $this->setComponents([
            'api'        => SapBotApi::class,
            'tagManager' => TagManager::class,
        ]);

        // Register plugin routes.
        $this->registerRoutes();

        // Register plugin event hooks.
        $this->registerEvents();
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel()
    {
        return new SettingsModel();
    }

    protected function settingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('sapbot/_settings', [
            'settings' => $this->getSettings()
        ]);
    }

    protected function registerRoutes()
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules = array_merge($event->rules, [
                'sapbot/test' => 'sapbot/test/response',
            ]);
        });

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_SITE_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules = array_merge($event->rules, [
                'sapbot/api/query' => 'sapbot/api/query',
                'sapbot/api/query-not-found' => 'sapbot/api/query-not-found',
            ]);
        });
    }

    protected function registerEvents()
    {
        // Synchronize tags with sap synonyms.
        Event::on(Elements::class, Elements::EVENT_BEFORE_SAVE_ELEMENT, [$this->tagManager, 'beforeSaveElement']);
        Event::on(Elements::class, Elements::EVENT_AFTER_DELETE_ELEMENT, [$this->tagManager, 'afterDeleteElement']);
    }
}
