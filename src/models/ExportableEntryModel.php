<?php

namespace studioespresso\exporter\models;

use craft\elements\Entry;
use craft\services\Entries;

class ExportableEntryModel extends ExportableElementTypeModel
{
    /**
     * References the class of the Element Type
     * @var string
     */
    public $elementType = Entry::class;

    /**
     * Label of the element type
     * @var string
     */
    public string $elementLabel = "Entries";

    public bool $shouldBeLocalised = true;

    /**
     * This function defines the element's attributes you want to make exportable
     * @return array
     */
    public function getElementAttributes(): bool|array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'URL',
            'slug' => 'Slug',
            'dateCreated' => 'Date created',
        ];
    }

    /**
     * This function defines the groups in which the element can be groups. Like sections for entries, forms for submissions , etc
     * @return array
     */
    public function getGroup(): array
    {
        return [
            "parameter" => "sectionId",
            "label" => \Craft::t('exporter', 'Section'),
            "instructions" => \Craft::t('exporter', 'Choose a group from which you want to start your export'),
            "items" => \Craft::$app->getEntries()->getEditableSections(),
        ];
    }

    /**
     * If the element's groups have an additional sub-group, define those here
     * @return bool|array
     */
    public function getSubGroup(): array|bool
    {
        return [
            'label' => \Craft::t('exporter', 'Entry type'),
            "instructions" => \Craft::t('exporter', 'Choose which entry-type you want to export'),
            'parameter' => 'id',
            'class' => Entries::class,
            'function' => 'getEntryTypesBySectionId',
        ];
    }
}
