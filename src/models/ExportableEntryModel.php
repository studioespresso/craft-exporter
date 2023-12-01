<?php

namespace studioespresso\exporter\models;

use craft\base\Element;
use craft\base\ElementInterface;
use craft\base\Model;
use craft\elements\Entry;
use craft\services\Sections;

class ExportableEntryModel extends ExportableElementTypeModel
{
    public $elementType = Entry::class;

    public string $elementLabel = "Entries";

    public function getGroup(): array
    {
        return [
            "parameter" => "sectionId",
            "label" => "Section",
            "instructions" => "Choose a group from which you want to start your export",
            "items" => \Craft::$app->getSections()->getEditableSections(),
        ];
    }

    public function getSubGroup(): array
    {
        return [
            'label' => "Entry type",
            "instructions" => "Choose which entry-type you want to export",
            'parameter' => 'id',
            'class' => Sections::class,
            'function' => 'getEntryTypesBySectionId',
        ];
    }

}