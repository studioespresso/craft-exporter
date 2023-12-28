<?php

namespace studioespresso\exporter\helpers;

use craft\base\Event;
use studioespresso\exporter\events\RegisterExportableElementTypes;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\models\ExportableElementTypeModel;

class ElementTypeHelper
{
    public const EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES = 'registerExportableElementTypes';

    /** @var ExportableElementTypeModel[] SUPPORTED_ELEMENT_TYPES */
    public const SUPPORTED_ELEMENT_TYPES = [];


    private static ?array $_supportedElementTypes = null;

    /**
     * @return ExportableElementTypeModel[] array
     */
    public function getAvailableElementTypes(): array
    {
        if (self::$_supportedElementTypes !== null) {
            return self::$_supportedElementTypes;
        }

        $event = new RegisterExportableElementTypes([
            'elementTypes' => self::SUPPORTED_ELEMENT_TYPES,
        ]);

        Event::trigger(self::class, self::EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES, $event);

        self::$_supportedElementTypes = array_merge(
            self::SUPPORTED_ELEMENT_TYPES,
            $event->elementTypes
        );

        return self::$_supportedElementTypes;
    }

    public function getElementTypesOnly(): array
    {
        if (self::$_supportedElementTypes == null) {
            $this->getAvailableElementTypes();
        }

        $types = [];
        foreach ($this->getAvailableElementTypes() as $model) {
            if (!$model->validate()) {
                \Craft::error("ExportableElementTypeModel is not configured correctly", Exporter::class);
                continue;
            }

            $types[$model->elementType] = $model->elementLabel;
        }

        return $types;
    }

    public function getElementTypeSettings($element)
    {
        $this->getAvailableElementTypes();
        if (!isset(self::$_supportedElementTypes[$element])) {
            // throw exception here
        }
        return self::$_supportedElementTypes[$element];
    }
}
