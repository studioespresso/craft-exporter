<?php

namespace studioespresso\exporter\services;

use craft\base\Component;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\fields\PlainText;
use studioespresso\exporter\services\formats\Xlsx;
use verbb\formie\fields\formfields\Email;
use verbb\formie\fields\formfields\MultiLineText;
use verbb\formie\fields\formfields\Name;

class ExportConfigurationService extends Component
{
    public function getAvailableElementTypes(): array
    {
        $types = [
            Entry::class,
            Category::class,
            Tag::class,
        ];

        return ['Craft' => $types];
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
            MultiLineText::class,
            Name::class,
            Email::class
        ];

        return $fields;
    }
}
