<?php

namespace studioespresso\exporter\console\controllers;

use Craft;
use craft\base\Element;
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

    public function actionDebug($id): bool
    {
        $export = ExportElement::find()->id($id)->one();
        $query = Exporter::$plugin->query->buildQuery($export);

        $attributes = array_values($export->getAttributes());
        $fields = array_values($export->getFields());
        $data[] = array_merge($attributes, $fields);

        foreach($query->limit(2)->all() as $element){
            $values = $element->toArray(array_keys($export->getAttributes()));
            // Convert values to strings
            $values = array_map(function ($item) {
                return (string)$item;
            }, $values);

            $row = array_combine(array_values($export->getAttributes()), $values);

            // Fetch the custom field content, already prepped
            $fieldValues = $export->parseFieldValues($element);
            $data[] = array_merge($row, $fieldValues);
        }

        return true;
    }
}