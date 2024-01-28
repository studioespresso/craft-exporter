<?php

namespace studioespresso\exporter\services;

use craft\base\Component;
use craft\elements\User;
use studioespresso\exporter\elements\ExportElement;

class ElementService extends Component
{
    public function getEditableExports(User $user): ?array
    {
        $allExports = ExportElement::find()->all();
        $exports = array_filter($allExports, function($export) use ($user) {
            if ($export->canView($user)) {
                return true;
            }
            return false;
        });
        return $exports;
    }
}
