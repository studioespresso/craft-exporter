<?php

namespace studioespresso\exporter\jobs;

use Craft;
use craft\base\Batchable;
use craft\base\Element;
use craft\db\QueryBatcher;
use craft\helpers\Db;
use craft\queue\BaseBatchedJob;
use studioespresso\exporter\elements\ExportElement;

class ExportJob extends BaseBatchedJob
{
    public $exportName;

    public $elementId;

    protected function defaultDescription(): string
    {
        return Craft::t('app', 'Running  {name}', [
            'name' => $this->exportName,
        ]);
        Db::each()
    }

    public function loadData(): Batchable
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
        $element = $export->elementType;
        $settings = $export->getSettings();
        /** @var $element Element */
        switch ($element) {
            default:
                $query = Craft::createObject($element)->find()->sectionId($settings['section']);
            break;
        }

        return new QueryBatcher($query);
    }

    protected function processItem(mixed $item): void
    {
        Craft::debug(
            Craft::t(
                'exporter',
                'Export: processing "{title}"',
                ['title' => $item->title]
            ),
            __METHOD__
        );
    }
}
