<?php

namespace studioespresso\exporter\events;

use craft\base\Event;
use studioespresso\exporter\fields\BaseFieldParser;

class RegisterExportableFieldTypes extends Event
{
    /** @var BaseFieldParser[] $fieldTypes */
    public array $fieldTypes = [];
}
