<?php

namespace studioespresso\exporter\variables;


use studioespresso\exporter\Exporter;

/**
 * The class name isn't important, but we've used something that describes
 * how it is applied, rather than what it does.
 *
 * You are only apt to need a single behavior, even if your plugin or module
 * provides multiple element types.
 */
class ExporterVariable
{
    public function getElementTypeSettings($element): array
    {
        return Exporter::getInstance()->elements->getElementTypeSettings($element);
    }

}
