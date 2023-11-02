<?php

namespace studioespresso\exporter\services;

use craft\base\Component;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\fields\PlainText;
use studioespresso\exporter\services\formats\Xlsx;

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

    public function getAvailableFileTypes(): array
    {
        $types = [
            Xlsx::class => 'Excel (xlsx)'
        ];

        return $types;
    }

    public function getSupportedFieldTypes(): array
    {
        $fields = [
          PlainText::class,
        ];

        return $fields;
    }
}
