<?php

namespace studioespresso\exporter\fields;

class DateTimeParser extends BaseFieldParser
{
    public function getValue($element, $handle)
    {
        return $element->getFieldValue($handle);
    }

    public function getOptionType(): string
    {
        return "select";
    }

    public function getOptions(): array
    {
        return [
            'd/M/Y',
            'm/d/Y',
        ];
    }
}
