<?php

namespace studioespresso\exporter\models;

use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Model;
use craft\elements\Category;
use craft\elements\Entry;
use craft\services\Sections;

class ExportableCategoryModel extends ExportableElementTypeModel
{
    public $elementType = Category::class;

    public string $elementLabel = "Categories";

    public function getGroup(): array
    {
        return [
            "parameter" => "groupId",
            "label" => "Group",
            "instructions" => "Choose a group from which you want to start your export",
            "items" => \Craft::$app->getCategories()->getEditableGroups(),
        ];
    }

    public function getSubGroup(): bool|array
    {
       return false;
    }

}