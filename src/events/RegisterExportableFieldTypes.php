<?php

namespace studioespresso\exporter\events;

use craft\base\Event;

class RegisterExportableFieldTypes extends Event
{
    public array $fieldTypes = [];
}