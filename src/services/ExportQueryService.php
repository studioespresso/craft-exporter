<?php

namespace studioespresso\exporter\services;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\elements\Category;
use craft\elements\db\ElementQuery;
use craft\elements\Entry;
use craft\elements\Tag;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\services\formats\Xlsx;

class ExportQueryService extends Component
{
    public function buildQuery(ExportElement $export): ElementQuery
    {
        $element = $export->elementType;
        $settings = $export->getSettings();
        $elementOptions = Exporter::getInstance()->elements->getElementTypeSettings($element);
        $limit = null;
        /** @var $element Element */
        $query = Craft::createObject($element)->find();

        if(isset($elementOptions['group'])) {
            $group = $elementOptions['group']['parameter'];
            $query->$group($settings['group']);
        }
        // TODO: Take run-settings into account here: limit, dates, etc
        return $query;
    }

    public function getFields(ExportElement $export, Element $element): array
    {
        $data = [];
        foreach ($export->getFields() as $field) {
            $data[$field] = $element->getFieldValue($field);
        }
        return $data;
    }


}
