<?php

namespace studioespresso\exporter\fields;

class MultiOptionsFieldParser extends OptionsFieldParser
{
    public function getValue($element, $field)
    {
        $selected = [];
        $property = $field['property'] ?? 'value';

        foreach ($element->getFieldValue($field['handle'])->getOptions() as $option) {
            if ($option->selected) {
                $selected[] = $option->$property;
            }
        }
        return $selected;
    }
}
