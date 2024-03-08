---
title: Registering field support - Element exporter plugin for Craft CMS
layout: doc
prev: false
description: Documentation for Element Exporter, a plugin for Craft CMS.
---


# Registering support for custom fields

The plugin comes with a number of built in parsers, all following the [BaseFieldParser](https://github.com/studioespresso/craft-exporter/blob/develop/src/fields/BaseFieldParser.php) class.

Each field is assigned a parser, with the PlainTextParser acting as a backup in case the selected parser can't return the value.

Registering plugin fields to a parser - either a built parser or your own, can be done through the ``EVENT_REGISTER_EXPORTABLE_FIELD_TYPES`` event.

In the example below, we add Formie's `Checkboxes` field to the built-in `MultiOptionsFieldParser::class`. 


````php
use studioespresso\exporter\helpers\FieldTypeHelper;
use studioespresso\exporter\events\RegisterExportableFieldTypes;
use studioespresso\exporter\fields\MultiOptionsFieldParser;

Event::on(
    FieldTypeHelper::class,
    FieldTypeHelper::EVENT_REGISTER_EXPORTABLE_FIELD_TYPES,
    function(RegisterExportableFieldTypes $event) {
        $parsers = $event->fieldTypes;
   
       $event->fieldTypes[MultiOptionsFieldParser::class] = array_merge($parsers[MultiOptionsFieldParser::class], [
            \verbb\formie\fields\formfields\Checkboxes::class, // @phpstan-ignore-line
       ]);
    }
);
````

## Included Parsers
- PlainTextParser
- RelationParser
- DateTimeParser
- TimeParser
- OptionsFieldParser
- MultiOptionsFieldParser
- MoneyFieldParser

There all live under the `studioespresso\exporter\fields\` namespace.
