<?php

namespace studioespresso\exporter\fields;

use Craft;
use craft\base\Element;

class RelationFieldParser extends BaseFieldParser
{
    public function getValue(Element $element, array $field)
    {
        $relation = $element->getFieldValue($field['handle'])->one();
        $property = $field['property'];

        if ($relation) {
            return $relation->$property;
        }

        return $relation->id;
    }

    public function getOptions(): array
    {
        return [
            'title' => Craft::t('app', 'Title'),
            'id' => Craft::t('app', 'ID'),
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
