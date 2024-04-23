<?php

namespace studioespresso\exporter\helpers;

use craft\base\Event;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\fields\Assets;
use craft\fields\Categories;
use craft\fields\Checkboxes;
use craft\fields\Color;
use craft\fields\Date;
use craft\fields\Dropdown;
use craft\fields\Email;
use craft\fields\Entries;
use craft\fields\Lightswitch;
use craft\fields\Money;
use craft\fields\MultiSelect;
use craft\fields\Number;
use craft\fields\PlainText;
use craft\fields\RadioButtons;
use craft\fields\Tags;
use craft\fields\Time;
use craft\fields\Url;
use studioespresso\exporter\events\RegisterExportableFieldTypes;
use studioespresso\exporter\fields\BaseFieldParser;
use studioespresso\exporter\fields\DateTimeParser;
use studioespresso\exporter\fields\MoneyFieldParser;
use studioespresso\exporter\fields\MultiOptionsFieldParser;
use studioespresso\exporter\fields\OptionsFieldParser;
use studioespresso\exporter\fields\PlainTextParser;
use studioespresso\exporter\fields\RelationFieldParser;
use studioespresso\exporter\fields\TimeParser;

class FieldTypeHelper
{
    public const EVENT_REGISTER_EXPORTABLE_FIELD_TYPES = 'registerExportableFieldTypes';

    public const SUPPORTED_PARSERS = [
        PlainTextParser::class => [],
        RelationFieldParser::class => [],
        DateTimeParser::class => [],
        TimeParser::class => [],
        OptionsFieldParser::class => [],
        MultiOptionsFieldParser::class => [],
        MoneyFieldParser::class => [],
    ];

    public const SUPPORTED_FIELD_TYPES = [
        RelationFieldParser::class => [
            Entries::class,
            Assets::class,
            Categories::class,
            Tags::class,
        ],
        DateTimeParser::class => [
            Date::class,
        ],
        TimeParser::class => [
            Time::class,
        ],
        OptionsFieldParser::class => [
            Dropdown::class,
            RadioButtons::class,
        ],
        MultiOptionsFieldParser::class => [
            MultiSelect::class,
            Checkboxes::class,
        ],
        MoneyFieldParser::class => [
            Money::class,
        ],
        PlainTextParser::class => [
            PlainText::class,
            Number::class,
            Email::class,
            Color::class,
            Url::class,
            Lightswitch::class,
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
            'fieldTypes' => self::SUPPORTED_PARSERS,
        ]);

        Event::trigger(self::class, self::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES, $event);

        self::$_supportedFieldTypes = array_merge_recursive(
            $event->fieldTypes,
            self::SUPPORTED_FIELD_TYPES,
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
