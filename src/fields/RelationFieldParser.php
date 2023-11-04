<?php

namespace studioespresso\exporter\fields;

class RelationFieldParser implements BaseFieldParser
{
    public function getValue($element, $handle)
    {
        $relation =  $element->getFieldValue($handle);
        if(isset($relation->title)) {
            return $relation->title;
        }
        return $relation->id;
    }

}