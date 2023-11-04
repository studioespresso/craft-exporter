<?php

namespace studioespresso\exporter\events;

use craft\base\Event;

class RegisterExportableElementTypes extends Event
{
    public array $elementTypes = [];
}