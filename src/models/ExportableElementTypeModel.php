<?php

namespace studioespresso\exporter\models;

use craft\base\Model;

abstract class ExportableElementTypeModel extends Model
{
    /**
     * References the class of the Element Type
     * @phpstan-ignore-next-line
     * @var string
     */
    public $elementType;

    /**
     * Label of the element type
     * @var string
     */
    public string $elementLabel = "";

    /**
     * This function defines the groups in which the element can be groups. Like sections for entries, forms for submissions , etc
     * @return array
     */
    abstract public function getGroup(): bool|array;

    /**
     * If the element's groups have an additional sub-group, define those here
     * @return bool|array
     */
    abstract public function getSubGroup(): bool|array;
}
