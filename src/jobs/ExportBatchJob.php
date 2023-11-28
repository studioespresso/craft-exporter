<?php

namespace studioespresso\exporter\jobs;

use Craft;
use craft\base\Batchable;
use craft\db\QueryBatcher;
use craft\errors\ElementNotFoundException;
use craft\queue\BaseBatchedJob;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;

class ExportBatchJob extends BaseBatchedJob
{
    public $exportName;

    public $elementId;

    /**
     * @var ExportElement
     */
    public $export;

    public $fields = [];

    public $attributes = [];

    public $runSettings = [];

    public $data = [];

    public string $fileName = '';

    protected function defaultDescription(): string
    {
        return Craft::t('app', 'Running  {name}', [
            'name' => $this->exportName,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function loadData(): Batchable
    {
        Craft::debug(
            Craft::t(
                'exporter',
                'Loading data for "{name}" job',
                ['name' => $this->exportName]
            ),
            __CLASS__
        );
        $this->export = ExportElement::findOne(['id' => $this->elementId]);

        if (!$this->export) {
            throw new ElementNotFoundException();
        }

        $query = Exporter::$plugin->query->buildQuery($this->export);

        // Set headers for data before we start.
        $attributes = array_values($this->attributes);
        $fields = $this->export->getHeadings();
        $this->data[0] = array_merge($attributes, $fields);

        return new QueryBatcher($query);
    }

    /**
     * @inheritDoc
     */
    protected function processItem(mixed $item): void
    {
        Craft::debug(json_encode($this->attributes), "JAN");
        $attributes = array_values($this->attributes);
        $fields = array_values($this->fields);
//        $data[] = array_merge($attributes, $fields);

        $values = $item->toArray(array_values($attributes));
        // Convert values to strings
        $values = array_map(function($item) {
            return (string)$item;
        }, $values);

        // Fetch the custom field content, already prepped
        $fieldValues = $this->export->parseFieldValues($item);

        $this->data[] = array_merge($values, $fieldValues);
    }


    public function execute($queue): void
    {
        parent::execute($queue);

        // Was this the last batch?
        if ($this->itemOffset >= $this->totalItems()) {
            Craft::debug(
                Craft::t(
                    'exporter',
                    'All data for "{name}" processed, running delivery now.',
                    ['name' => $this->exportName]
                ),
                __CLASS__
            );
            // Now that we have the data, pass it to a specific service to generate the file
            $settings = $this->export->getSettings();
            $file = false;
            switch ($settings['fileType']) {
                case 'xlsx':
                    $xlsx = new \studioespresso\exporter\services\formats\Xlsx();
                    $file = $xlsx->create($this->export, $this->data, $this->fileName);
                    break;
                case 'csv':
                    $csv = new \studioespresso\exporter\services\formats\Csv();
                    $file = $csv->create($this->export, $this->data, $this->fileName);
                    break;
            }
            if ($file) {
                // Once the file has been generated, deliver the file according to the selected method
                if (Exporter::getInstance()->mail->send($this->export, $file)) {
                   // unlink($file);
                }
            }
        }
    }
}
