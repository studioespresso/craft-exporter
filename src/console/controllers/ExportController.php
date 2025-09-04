<?php

namespace studioespresso\exporter\console\controllers;

use craft\console\Controller;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;

/**
 * Run your exports from the command line
 */
class ExportController extends Controller
{
    /**
     * Pass an export ID to add that export to the queue
     * @param $id
     * @return bool
     */
    public function actionRun($id): bool
    {
        $export = ExportElement::find()->id($id)->one();
        Exporter::getInstance()->queue->addExport($export);
        return true;
    }
}
