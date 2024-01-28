<?php

namespace studioespresso\exporter\services;

use Craft;
use craft\base\Component;
use craft\elements\User;
use craft\mail\Message;
use craft\web\View;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\models\Settings;

class ElementService extends Component
{
    public function getEditableExports(User $user): ?array
    {
        $allExports = ExportElement::find()->all();
        $exports = array_filter($allExports, function ($export) use ($user) {
            if ($export->canView($user)) {
                return true;
            }
            return false;
        });
        return $exports;
    }
}
