<?php

namespace studioespresso\exporter\jobs;

use Craft;
use craft\base\Element;
use craft\errors\ElementNotFoundException;
use craft\helpers\Console;
use craft\queue\BaseJob;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use studioespresso\exporter\elements\ExportElement;
use yii\queue\RetryableJobInterface;

class ExportJob extends BaseJob implements RetryableJobInterface
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

        if(!$export) {
            throw new ElementNotFoundException();
        }

        $element = $export->elementType;
        $settings = $export->getSettings();
        $limit = null;
        /** @var $element Element */
        switch ($element) {
            default:
                $query = Craft::createObject($element)->find()->sectionId($settings['section']);
                break;
        }

        $data[] = array_values($export->getAttributes());

        $total = (clone $query)->count();
        $progress = 0;
        foreach($query->all() as $element){
            $this->setProgress($queue, $progress / $total);
            $progress++;

            $values = $element->toArray(array_keys($export->getAttributes()));
            // Convert values to strings
            $values = array_map(function ($item) {
                return (string)$item;
            }, $values);

            $row = array_combine(array_values($export->getAttributes()), $values);

            // Fetch the custom field content, already prepped
            $fieldValues = [];
            $data[] = array_merge($row, $fieldValues);
        }

        // Normalise the columns. Due to repeaters/table fields, some rows might not have the correct columns.
        // We need to have all rows have the same column definitions.
        // First, find the row with the largest columns to use as our template for all other rows
        $counts = array_map('count', $data);
        $key = array_flip($counts)[max($counts)];
        $largestRow = $data[$key];

        // Now we have the largest row in columns, normalise all other rows, filling in blanks
        $keys = array_keys($largestRow);
        $template = array_fill_keys($keys, '');

        $exportData = array_map(function ($item) use ($template) {
            return array_merge($template, $item);
        }, $data);


        $rows = array_map(function ($row) {
            return array_values($row);
        }, $exportData);

        array_unshift($rows, array_keys($exportData[0]));

        try {
            ob_end_clean();
        } catch (\Throwable $e) {

        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data);
        $writer = new Xlsx($spreadsheet);
        $path = Craft::$app->getPath()->getTempPath() . '/export.xlsx';
        $writer->save($path);
    }



    /**
     * @inheritDoc
     */
    public function getTtr()
    {
        // TODO: Implement getTtr() method.
    }

    /**
     * @inheritDoc
     */
    public function canRetry($attempt, $error)
    {
        // TODO: Implement canRetry() method.
    }
}
