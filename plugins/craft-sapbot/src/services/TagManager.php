<?php

namespace csps\sapbot\services;

use craft\base\Component;
use craft\elements\Tag;
use craft\events\ElementEvent;
use csps\sapbot\Plugin;
use GuzzleHttp\Exception\ClientException;

class TagManager extends Component
{
    public function beforeSaveElement(ElementEvent $event)
    {
        // Handle logic when a sap bot query tag is saved.
        if (get_class($event->element) === Tag::class && $event->element->group->handle === 'sapBotQuery') {
            if ($event->isNew) {
                $synonym = Plugin::getInstance()->api->synonyms()->create('query', $event->element->title);
            } else {
                $synonym = Plugin::getInstance()->api->synonyms()->update('query', $event->element->slug, $event->element->title);
            }
            // Update craft tag slug using sap synonym slug so we can keep both of them in-sync.
            $event->element->slug = $synonym->slug;
        }
    }

    public function afterDeleteElement(ElementEvent $event)
    {
        // Handle logic when a sap bot query tag is deleted.
        if (is_subclass_of($event->element, Tag::class) && $event->element->group->handle === 'sapBotQuery') {
            try {
               Plugin::getInstance()->api->synonyms()->delete('query', $event->element->slug);
            } catch (ClientException $e) {
                // Most likely a desync with sap bot service.
            }
        }
    }
}
