<?php

namespace studioespresso\exporter\models;

use craft\elements\Category;

class ExportableCategoryModel extends ExportableElementTypeModel
{
    /**
     * References the class of the Element Type
     * @var string
     */
    public $elementType = Category::class;

    /**
     * Label of the element type
     * @var string
     */
    public string $elementLabel = "Categories";

    /**
     * This function defines the groups in which the element can be groups. Like sections for entries, forms for submissions , etc
     * @return array
     */
    public function getGroup(): array
    {
        return [
            "parameter" => "groupId",
            "label" => "Group",
            "instructions" => "Choose a group from which you want to start your export",
            "items" => \Craft::$app->getCategories()->getEditableGroups(),
        ];
    }

    /**
     * If the element's groups have an additional sub-group, define those here
     * @return bool|array
     */
    public function getSubGroup(): bool|array
    {
        return false;
    }
}
