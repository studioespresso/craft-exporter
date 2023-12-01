<?php

namespace studioespresso\exporter\models;

use craft\elements\Category;

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