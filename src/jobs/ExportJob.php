<?php

namespace studioespresso\exporter\jobs;

use Craft;
use craft\base\Element;
use craft\errors\ElementNotFoundException;
use craft\helpers\Console;
use craft\queue\BaseBatchedJob;
use craft\queue\BaseJob;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use yii\queue\RetryableJobInterface;

class ExportJob extends BaseBatchedJob implements RetryableJobInterface
{
    public $exportName;

    public $elementId;

    protected function defaultDescription(): string
    {
        return Craft::t('app', 'Running  {name}', [
            'name' => $this->exportName,
        ]);
    }

    public function execute($queue): void
    {
        Craft::debug(
            Craft::t(
                'exporter',
                'Loading data for "{name}" job',
                ['name' => $this->exportName]
            ),
            __METHOD__
        );
        $export = ExportElement::findOne(['id' => $this->elementId]);

        if (!$export) {
            throw new ElementNotFoundException();
        }

        $query = Exporter::$plugin->query->buildQuery($export);

        $attributes = array_values($export->getAttributes());
        $fields = array_values($export->getFields());
        $data[] = array_merge($attributes, $fields);

        $total = (clone $query)->count();
        $progress = 0;


        foreach ($query->all() as $element) {
            $this->setProgress($queue, $progress / $total);
            $progress++;

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

        // Now that we have the data, pass it to a specific service to generate the file


        // Once the file has been generated, deliver the file according to the selected method
    }
}
