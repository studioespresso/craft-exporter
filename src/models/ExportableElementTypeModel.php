<?php

namespace studioespresso\exporter\models;

use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Model;

abstract class ExportableElementTypeModel extends Model
{
    public $elementType;

    public string $elementLabel = "";

    abstract public function getGroup(): bool|array;

    abstract public function getSubGroup(): bool|array;

}