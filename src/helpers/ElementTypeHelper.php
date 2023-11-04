<?php

namespace studioespresso\exporter\helpers;

use craft\base\ElementInterface;
use craft\base\Event;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\models\CategoryGroup;
use craft\services\Categories;
use craft\services\Sections;
use studioespresso\exporter\events\RegisterExportableElementGroups;
use studioespresso\exporter\events\RegisterExportableElementTypes;

class ElementTypeHelper
{
    public const EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES = 'registerExportableElementTypes';

    public const EVENT_REGISTER_EXPORTABLE_ELEMENT_GROUPS = 'registerExportableElementGroups';

    public const SUPPORTED_ELEMENT_TYPES = [
        Entry::class => 'Entries',
        Category::class => "Categories",
        Tag::class => "Tags"
    ];


    public const SUPPORT_ELEMENT_GROUPS = [];

    private static ?array $_supportedElementTypes = null;

    private static ?array $_supportedElementGroups = null;


    public function getAvailableElementTypes(): array
    {
        if (self::$_supportedElementTypes !== null) {
            return self::$_supportedElementTypes;
        }

        $event = new RegisterExportableElementTypes([
            'elementTypes' => self::SUPPORTED_ELEMENT_TYPES,
        ]);

        Event::trigger(self::class, self::EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES, $event);

        self::$_supportedElementTypes = array_merge(
            self::SUPPORTED_ELEMENT_TYPES,
            $event->elementTypes
        );

        return self::$_supportedElementTypes;
    }

    public function getExportElementGroups()
    {
        if (self::$_supportedElementGroups !== null) {
            return self::$_supportedElementGroups;
        }

        $event = new RegisterExportableElementGroups([
            'elementGroups' => self::SUPPORT_ELEMENT_GROUPS,
        ]);

        Event::trigger(self::class, self::EVENT_REGISTER_EXPORTABLE_ELEMENT_GROUPS, $event);

        self::$_supportedElementGroups = array_merge(
            self::SUPPORT_ELEMENT_GROUPS,
            $event->elementGroups
        );

        return self::$_supportedElementGroups;
    }

    public function getGroupItemsForType($element)
    {
        $this->getExportElementGroups();
        if(!isset(self::$_supportedElementGroups[$element])) {
            // throw exception here
        }
        return self::$_supportedElementGroups[$element];
    }
}