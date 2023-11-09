<?php

namespace studioespresso\exporter\fields;

use craft\base\Element;

class RelationFieldParser extends BaseFieldParser
{
    public function getValue(Element $element, string $handle)
    {
        $relation = $element->getFieldValue($handle)->one();
        if (isset($relation->title)) {
            return $relation->title;
        }
        return $relation->id;
    }

    public function getOptions(): array
    {
        return [
            "Title",
            "ID",
        ];
    }

    public function getOptionType(): string
    {
        return "select";
    }
}
