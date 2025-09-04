<?php

namespace studioespresso\exporter\console\controllers;

use craft\console\Controller;
use craft\elements\db\UserQuery;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;

/**
 * For development purposes only
 */
class DebugController extends Controller
{
    /**
     * Test the export parsing logic and dumps the query data
     * @param $id
     * @return bool
     */
    public function actionRun($id): bool
    {
        $export = ExportElement::find()->id($id)->one();
        /** @var UserQuery $query */
        $query = Exporter::$plugin->query->buildQuery($export);

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
        dd($data);

        return true;
    }
}
