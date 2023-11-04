<?php

namespace studioespresso\exporter\fields;

interface BaseFieldParser
{
    public function getValue($element, $handle);

}