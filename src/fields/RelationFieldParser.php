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
}
