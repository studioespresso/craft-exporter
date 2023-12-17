<?php

namespace studioespresso\exporter\fields;

class TimeParser extends BaseFieldParser
{
    public function getValue($element, array $field)
    {
        $value = $element->getFieldValue($field['handle']);
        if ($value) {
            return $value->format($field['property']);
        }
    }

    public function getOptionType(): string
    {
        return "select";
    }


    public function getOptionLabel(): string|bool
    {
        return false;
    }


    public function getOptionDescription(): string|bool
    {
        return false;
    }

    public function getOptions(): array
    {
        return [
            'H:i:s' => 'H:i:s',
            'H:i' => 'H:i',
        ];
    }
}
