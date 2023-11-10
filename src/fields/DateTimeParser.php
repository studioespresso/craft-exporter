<?php

namespace studioespresso\exporter\fields;

class DateTimeParser extends BaseFieldParser
{
    public function getValue($element, array $field)
    {
        $value =  $element->getFieldValue($field['handle']);
        if ($value) {
            return $value->format($field['property']);
        }
    }

    public function getOptionType(): string
    {
        return "select";
    }

    public function getOptions(): array
    {
        return [
            'd/M/Y' => 'd/M/Y',
            'm/d/Y' => 'm/d/Y',
        ];
    }
}
