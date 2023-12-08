<?php

namespace studioespresso\exporter\helpers;

use craft\base\Event;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\Color;
use craft\fields\Date;
use craft\fields\Email;
use craft\fields\Entries;
use craft\fields\Lightswitch;
use craft\fields\Number;
use craft\fields\PlainText;
use craft\fields\RadioButtons;
use craft\fields\Tags;
use craft\fields\Url;
use studioespresso\exporter\events\RegisterExportableFieldTypes;
use studioespresso\exporter\fields\BaseFieldParser;
use studioespresso\exporter\fields\DateTimeParser;
use studioespresso\exporter\fields\PlainTextParser;
use studioespresso\exporter\fields\RelationFieldParser;

class FieldTypeHelper
{
    public const EVENT_REGISTER_EXPORTABLE_FIELD_TYPES = 'registerExportableFieldTypes';

    public const SUPPORTED_FIELD_TYPES = [
        PlainTextParser::class => [
            PlainText::class,
            Number::class,
            Lightswitch::class,
            Email::class,
            Color::class,
            Url::class,
            RadioButtons::class,
        ],
        RelationFieldParser::class => [
            Entries::class,
            Assets::class,
            Categories::class,
            Tags::class,
        ],
        DateTimeParser::class => [
            Date::class,
        ],
    ];


    private static ?array $_supportedFieldTypes = null;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->getAvailableFieldTypes();
    }

    public function getAvailableFieldTypes(): array
    {
        if (self::$_supportedFieldTypes !== null) {
            return self::$_supportedFieldTypes;
        }

        $event = new RegisterExportableFieldTypes([
            'fieldTypes' => self::SUPPORTED_FIELD_TYPES,
        ]);

        Event::trigger(self::class, self::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES, $event);

        self::$_supportedFieldTypes = array_merge(
            self::SUPPORTED_FIELD_TYPES,
            $event->fieldTypes
        );

        return self::$_supportedFieldTypes;
    }

    public function isFieldSupported(FieldInterface $field)
    {
        $item = array_filter(self::$_supportedFieldTypes, function($fields) use ($field) {
            foreach ($fields as $f) {
                if ($f === get_class($field)) {
                    return true;
                }
            }
        });

        $parser = array_keys($item);
        return reset($parser);
    }

    public function getParser(Field $field): BaseFieldParser|bool
    {
        if ($this->isFieldSupported($field)) {
            return \Craft::createObject($this->isFieldSupported($field));
        }
        return false;
    }
}
