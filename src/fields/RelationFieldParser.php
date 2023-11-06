<?php

namespace studioespresso\exporter\fields;

use craft\base\Element;

class RelationFieldParser implements BaseFieldParser
{
    public function getValue(Element $element, string $handle)
    {
        $relation =  $element->getFieldValue($handle)->one();
        if(isset($relation->title)) {
            return $relation->title;
        }
        return $relation->id;
    }

}