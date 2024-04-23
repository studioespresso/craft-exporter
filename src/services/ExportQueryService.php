<?php

namespace studioespresso\exporter\services;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\elements\db\ElementQuery;
use craft\errors\FieldNotFoundException;
use craft\helpers\DateTimeHelper;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\fields\PlainTextParser;
use yii\base\Exception;

class ExportQueryService extends Component
{
    public function buildQuery(ExportElement $export): ElementQuery
    {
        $element = $export->elementType;
        $settings = $export->getSettings();
        $elementOptions = Exporter::getInstance()->elements->getElementTypeSettings($element);
        /** @var Element $element */

        $query = Craft::createObject($element)->find()->siteId($settings['sites'] ?? '*');

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
                        "< {$dateEnd->format(\DateTime::ATOM)}",
                    ]);
                    break;
                case 'dateRange':
                    $dateStart = DateTimeHelper::toDateTime($runSettings['dateStart']);
                    $dateEnd = DateTimeHelper::toDateTime($runSettings['dateEnd']);
                    $query->dateCreated(['and',
                        ">= {$dateStart->format(\DateTime::ATOM)}",
                        "< {$dateEnd->format(\DateTime::ATOM)}",
                    ]);
                    break;
                case 'limit':
                    $query->limit($runSettings['dateEnd']);
                    break;
            }
        }

        return $query;
    }

    public function mockElement(ExportElement $export): Element|null
    {
        try {
            $elementType = $export->elementType;
            $settings = $export->getSettings();
            $elementOptions = Exporter::getInstance()->elements->getElementTypeSettings($elementType);

            $element = Craft::createObject($elementType);
            if (isset($elementOptions['group'])) {
                $group = $elementOptions['group']['parameter'];
                $element->$group = $settings['group'];
            }
            return $element;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getFields(ExportElement $export, Element $element): array
    {
        $data = [];
        $layout = $element->getFieldLayout();
        foreach ($export->getFields() as $field) {
            try {
                if (!$field['handle']) {
                    continue;
                }

                $craftField = $layout->getFieldByHandle($field['handle']);
                $parser = Exporter::getInstance()->fields->isFieldSupported($craftField);

                if (!$parser) {
                    $parser = PlainTextParser::class;
                }
                $object = Craft::createObject($parser);

                if (!isset($field['handle'])) {
                    throw new FieldNotFoundException($craftField->uid, "Field not found");
                }

                $data[$field['handle']] = $object->getValue($element, $field);
            } catch (Exception $e) {
                Craft::error($e->getMessage(), Exporter::class);
                continue;
            }
        }
        return $data;
    }
}
