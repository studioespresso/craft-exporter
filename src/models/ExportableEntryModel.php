<?php

namespace studioespresso\exporter\models;

use craft\elements\Entry;
use craft\services\Sections;

class ExportableEntryModel extends ExportableElementTypeModel
{
    /**
     * References the class of the Element Type
     * @phpstan-ignore-next-line
     * @var string
     */
    public $elementType = Entry::class;

    /**
     * Label of the element type
     * @var string
     */
    public string $elementLabel = "Entries";

    /**
     * This function defines the groups in which the element can be groups. Like sections for entries, forms for submissions , etc
     * @return array
     */
    public function getGroup(): array
    {
        return [
            "parameter" => "sectionId",
            "label" => "Section",
            "instructions" => "Choose a group from which you want to start your export",
            "items" => \Craft::$app->getSections()->getEditableSections(),
        ];
    }

    /**
     * If the element's groups have an additional sub-group, define those here
     * @return bool|array
     */
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
