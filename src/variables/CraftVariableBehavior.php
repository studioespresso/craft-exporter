<?php

namespace studioespresso\exporter\variables;

use Craft;
use studioespresso\exporter\elements\db\ExportElementQuery;
use studioespresso\exporter\elements\ExportElement;
use yii\base\Behavior;

/**
 * The class name isn't important, but we've used something that describes
 * how it is applied, rather than what it does.
 *
 * You are only apt to need a single behavior, even if your plugin or module
 * provides multiple element types.
 */
class CraftVariableBehavior extends Behavior
{
    public function exports(array $criteria = []): ExportElementQuery
    {
        // Create a query via your element type, and apply any passed criteria:
        return Craft::configure(ExportElement::find(), $criteria);
    }
}
