<?php

namespace studioespresso\exporter\fields;

class PlainTextParser implements BaseFieldParser
{
    public function getValue($element, $handle)
    {
        return $element->getFieldValue($handle);
    }

}