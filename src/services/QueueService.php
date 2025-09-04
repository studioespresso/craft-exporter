<?php

namespace studioespresso\exporter\services;

use Craft;
use craft\base\Component;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\jobs\ExportBatchJob;

class QueueService extends Component
{
    public function addExport(ExportElement $export): void
    {
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
            ->push(new ExportBatchJob([
                'elementId' => $export->id,
                'exportName' => $export->name,
                'fields' => $export->getFields(),
                'attributes' => $export->getAttributes(),
                'runSettings' => $export->getRunSettings(),
            ]));
    }
}
