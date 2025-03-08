<?php

namespace studioespresso\exporter\console\controllers;

use Craft;
use craft\console\Controller;
use craft\elements\db\UserQuery;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\jobs\ExportBatchJob;

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
            ->push(new ExportBatchJob([
                'elementId' => $export->id,
                'exportName' => $export->name,
                'fields' => $export->getFields(),
                'attributes' => $export->getAttributes(),
                'runSettings' => $export->getRunSettings(),
            ]));

        return true;
    }

    public function actionDebug($id): bool
    {
        $export = ExportElement::find()->id($id)->one();
        /** @var UserQuery $query */
        $query = Exporter::$plugin->query->buildQuery($export);
        var_dump($query->all());
        exit;

        $attributes = array_values($export->getAttributes());
        $fields = $export->getHeadings();
        $data[] = array_merge($attributes, $fields);

        foreach ($query->limit(1)->all() as $element) {
            $values = $element->toArray(array_keys($export->getAttributes()));
            // Convert values to strings
            $row = array_map(function($item) {
                return (string)$item;
            }, $values);

            // Fetch the custom field content, already prepped
            $fieldValues = $export->parseFieldValues($element);
            // var_dump($row); exit;
            $data[] = array_merge($row, $fieldValues);
        }

        return true;
    }
}
