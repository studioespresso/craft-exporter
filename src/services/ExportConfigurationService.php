<?php

namespace studioespresso\exporter\services;

use craft\base\Component;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;

class ExportConfigurationService extends Component
{
    public function getAvailableElementTypes(): array
    {
        $types = [
            Entry::class,
            Category::class,
            Tag::class,
        ];

        return $types;
    }
}
