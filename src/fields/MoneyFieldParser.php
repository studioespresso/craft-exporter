<?php

namespace studioespresso\exporter\fields;

class MoneyFieldParser extends BaseFieldParser
{
    public function getValue($element, $field)
    {
        $field = $element->getFieldValue($field['handle']);
        return $field->getAmount();
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
        // TODO: Implement getOptionLabel() method.
    }

    protected function getOptionDescription(): string|bool
    {
        // TODO: Implement getOptionDescription() method.
    }
}
