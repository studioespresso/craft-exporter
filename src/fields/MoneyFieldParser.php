<?php

namespace studioespresso\exporter\fields;

class MoneyFieldParser extends BaseFieldParser
{
    public function getValue($element, $field)
    {
        $field = $element->getFieldValue($field['handle']);
        if ($field) {
            return $field->getAmount();
        }
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getOptionType(): string|bool
    {
        return false;
    }

    protected function getOptionLabel(): string|bool
    {
        return false;
    }

    protected function getOptionDescription(): string|bool
    {
        return false;
    }
}
