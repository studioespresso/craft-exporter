<?php

namespace studioespresso\exporter\events;

use craft\base\Event;

class RegisterExportableElementGroups extends Event
{
    public array $elementGroups = [];
}