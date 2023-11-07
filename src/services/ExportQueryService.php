<?php

namespace studioespresso\exporter\services;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\elements\Category;
use craft\elements\db\ElementQuery;
use craft\elements\Entry;
use craft\elements\Tag;
use craft\helpers\DateTimeHelper;
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
        $query = Craft::createObject($element)->find()->siteId($settings['sites']);

        if (isset($elementOptions['group'])) {
            $group = $elementOptions['group']['parameter'];
            $query->$group($settings['group']);
        }

        $runSettings = $export->getRunSettings();
        if ($runSettings) {

            switch ($runSettings['elementSelection']) {
                case 'dateFrom':
                    $dateStart = DateTimeHelper::toDateTime($runSettings['dateStart']);
                    $dateEnd = DateTimeHelper::now();
                    $query->dateCreated(['and',
                        ">= {$dateStart->format(\DateTime::ATOM)}",
                        "< {$dateEnd->format(\DateTime::ATOM)}"
                    ]);
                    break;
                case 'dateRange':
                    $dateStart = DateTimeHelper::toDateTime($runSettings['dateStart']);
                    $dateEnd = DateTimeHelper::toDateTime($runSettings['dateEnd']);
                    $query->dateCreated(['and',
                        ">= {$dateStart->format(\DateTime::ATOM)}",
                        "< {$dateEnd->format(\DateTime::ATOM)}"
                    ]);
                    break;
                case 'limit':
                    $query->limit($runSettings['dateEnd']);
                    break;
            }
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
            if (!$parser) {
                $data[$handle] = "";
                continue;
            }
            $object = Craft::createObject($parser);

            $data[$handle] = $object->getValue($element, $handle);
        }

        return $data;
    }


}
