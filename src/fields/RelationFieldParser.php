<?php

namespace studioespresso\exporter\fields;

use Craft;
use craft\base\Element;

class RelationFieldParser extends BaseFieldParser
{
    public function getValue(Element $element, array $field)
    {
        /** @var Element|null $element */

        $relations = $element->getFieldValue($field['handle'])->all();
        $property = $field['property'];

        $result = [];
        foreach ($relations as $relation) {

            if (isset($relation->$property)) {
                $result[] = $relation->$property;
            } else {
                $result[] = $relation->id;
            }
        }

        if(empty($result)) {
            return '';
        }

        if(count($result) === 1) {
            return $result[0];
        }

        return implode(',', $result);
    }

    public function getOptions(): array
    {
        return [
            'title' => Craft::t('app', 'Title'),
            'id' => Craft::t('app', 'ID'),
            'url' => Craft::t('app', 'URL'),
        ];
    }

    public function getOptionType(): string
    {
        return "select";
    }

    public function getOptionLabel(): string|bool
    {
        return Craft::t('exporter', 'Select element property');
    }

    public function getOptionDescription(): string|bool
    {
        return Craft::t('exporter', 'Select which property on the selected element you want to include in the export.');
    }
}
