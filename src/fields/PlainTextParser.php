<?php

namespace studioespresso\exporter\fields;

class PlainTextParser extends BaseFieldParser
{
    public function getValue($element, $handle)
    {
        return $element->getFieldValue($handle);
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getOptionType(): string|bool
    {
        return false;
    }
}
