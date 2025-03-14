<?php

namespace studioespresso\exporter\variables;

use craft\base\Field;
use craft\db\Query;
use ReflectionClass;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\fields\BaseFieldParser;
use studioespresso\exporter\jobs\ExportBatchJob;
use studioespresso\exporter\models\ExportableElementTypeModel;

/**
 * The class name isn't important, but we've used something that describes
 * how it is applied, rather than what it does.
 *
 * You are only apt to need a single behavior, even if your plugin or module
 * provides multiple element types.
 */
class ExporterVariable
{
    public function listenForJob(ExportElement $export)
    {
        $class = ExportBatchJob::class;
        $reflect = new ReflectionClass($class);

        $query = new Query();
        $query->from("{{%queue}}")
            ->select('*')
            ->where(['like', 'job', "%{$reflect->getShortName()}%", false])
            ->andWhere(['like', 'job', "%{$export->id}%", false]);

        return $query->one() ?? false;
    }

    public function getElementTypeSettings($element): ExportableElementTypeModel
    {
        return Exporter::getInstance()->elements->getElementTypeSettings($element);
    }

    public function getSubGroupItems($options, $id)
    {
        $object = \Craft::createObject($options['class']);
        $function = $options['function'];
        return $object->$function($id);
    }

    public function getFieldParser(Field $field): BaseFieldParser|bool
    {
        return Exporter::getInstance()->fields->getParser($field);
    }

    public function getIgnoredFieldTypes(): array
    {
        return Exporter::getInstance()->fields->getIgnoredFieldTypes();
    }

    public function getClass($object)
    {
        return get_class($object);
    }
}
