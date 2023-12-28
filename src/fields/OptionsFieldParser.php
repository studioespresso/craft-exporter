<?php

namespace studioespresso\exporter\fields;

use Craft;

class OptionsFieldParser extends BaseFieldParser
{
    public function getValue($element, $field)
    {
        $property = $field['property'] ?? 'value';
        return $element->getFieldValue($field['handle'])->$property;
    }

    public function getOptions(): array
    {
        return [
            'label' => 'Label',
            'value' => 'Value',
        ];
    }

    public function getOptionType(): string|bool
    {
        return "select";
    }

    public function getOptionLabel(): string|bool
    {
        return Craft::t('exporter', 'Select field property');
    }

    public function getOptionDescription(): string|bool
    {
        return Craft::t('exporter', 'Select which property you want to export.');
    }
}
