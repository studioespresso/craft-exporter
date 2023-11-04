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
use studioespresso\exporter\helpers\FieldTypeHelper;
use studioespresso\exporter\services\formats\Xlsx;
use verbb\formie\Formie;

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
        $layout = $element->getFieldLayout();
        foreach ($export->getFields() as $handle) {
            $parser = Exporter::getInstance()->fields->isFieldSupported($layout->getFieldByHandle($handle));
            if(!$parser) {
                continue;
            }
            $object = Craft::createObject($parser);

            $data[$handle] = $object->getValue($element, $handle);
        }

        return $data;
    }


}
