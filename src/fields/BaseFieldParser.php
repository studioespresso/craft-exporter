<?php

namespace studioespresso\exporter\fields;

use craft\base\Element;

interface BaseFieldParser
{
    public function getValue(Element $element, string $handle);

}