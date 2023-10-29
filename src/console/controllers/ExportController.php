<?php

namespace studioespresso\exporter\console\controllers;

use Craft;
use craft\console\Controller;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\jobs\ExportJob;

class ExportController extends Controller
{
    public function actionRun($id): bool
    {
        $export = ExportElement::find()->id($id)->one();
        Craft::debug(
            Craft::t(
                'exporter',
                'Adding "{name}" job to the queue',
                ['name' => $export->name]
            ),
            __METHOD__
        );
        Craft::$app->getQueue()
            ->ttr(Exporter::$plugin->getSettings()->ttr)
            ->priority(Exporter::$plugin->getSettings()->priority)
            ->push(new ExportJob([
                'elementId' => $export->id,
                'exportName' => $export->name
            ]));

        return true;
    }
}