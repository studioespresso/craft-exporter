<?php

namespace studioespresso\exporter\helpers;

use craft\base\Event;
use studioespresso\exporter\events\RegisterExportableElementTypes;
use studioespresso\exporter\events\RegisterExportableFieldTypes;
use studioespresso\exporter\fields\DateTimeParser;
use studioespresso\exporter\fields\MultiOptionsFieldParser;
use studioespresso\exporter\fields\OptionsFieldParser;
use studioespresso\exporter\fields\PlainTextParser;
use studioespresso\exporter\fields\RelationFieldParser;
use studioespresso\exporter\models\ExportableFormieSubmissionModel;

class PluginHelper
{
    public function registerRedactor()
    {
        Event::on(
            FieldTypeHelper::class,
            FieldTypeHelper::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES,
            function(RegisterExportableFieldTypes $event) {
                $parsers = $event->fieldTypes;
                $parsers[PlainTextParser::class][] = \craft\redactor\Field::class; // @phpstan-ignore-line
                $event->fieldTypes = $parsers;
            });
    }


    public function registerCKEditor()
    {
        Event::on(
            FieldTypeHelper::class,
            FieldTypeHelper::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES,
            function(RegisterExportableFieldTypes $event) {
                $parsers = $event->fieldTypes;
                $parsers[PlainTextParser::class][] = \craft\ckeditor\Field::class; // @phpstan-ignore-line
                $event->fieldTypes = $parsers;
            });
    }

    public function registerFormie()
    {
        Event::on(
            ElementTypeHelper::class,
            ElementTypeHelper::EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES,
            function(RegisterExportableElementTypes $event) {
                $model = new ExportableFormieSubmissionModel();
                $event->elementTypes = array_merge($event->elementTypes, [
                    /** @phpstan-ignore-next-line */
                    \verbb\formie\elements\Submission::class => $model,
                ]);
            });

        Event::on(
            FieldTypeHelper::class,
            FieldTypeHelper::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES,
            function(RegisterExportableFieldTypes $event) {
                $parsers = $event->fieldTypes;

                $event->fieldTypes[PlainTextParser::class] = array_merge($parsers[PlainTextParser::class], [
                    \verbb\formie\fields\formfields\Email::class, // @phpstan-ignore-line
                    \verbb\formie\fields\formfields\SingleLineText::class, // @phpstan-ignore-line
                    \verbb\formie\fields\formfields\MultiLineText::class, // @phpstan-ignore-line
                    \verbb\formie\fields\formfields\Phone::class, // @phpstan-ignore-line
                    \verbb\formie\fields\formfields\Agree::class, // @phpstan-ignore-line
                    \verbb\formie\fields\formfields\Number::class, // @phpstan-ignore-line
                ]);

                $event->fieldTypes[DateTimeParser::class] = array_merge($parsers[DateTimeParser::class], [
                    \verbb\formie\fields\formfields\Date::class, // @phpstan-ignore-line
                ]);

                $event->fieldTypes[OptionsFieldParser::class] = array_merge($parsers[OptionsFieldParser::class], [
                    \verbb\formie\fields\formfields\Radio::class, // @phpstan-ignore-line
                    \verbb\formie\fields\formfields\Dropdown::class, // @phpstan-ignore-line
                ]);

                $event->fieldTypes[MultiOptionsFieldParser::class] = array_merge($parsers[MultiOptionsFieldParser::class], [
                    \verbb\formie\fields\formfields\Checkboxes::class, // @phpstan-ignore-line
                ]);

                $event->fieldTypes[RelationFieldParser::class] = array_merge($parsers[RelationFieldParser::class], [
                    \verbb\formie\fields\formfields\Entries::class, // @phpstan-ignore-line
                    \verbb\formie\fields\formfields\Categories::class, // @phpstan-ignore-line
                ]);
            });
    }
}
