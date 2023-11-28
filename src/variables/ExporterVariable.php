<?php

namespace studioespresso\exporter\variables;

use craft\base\Field;
use craft\db\Query;
use studioespresso\exporter\elements\ExportElement;
use studioespresso\exporter\Exporter;
use studioespresso\exporter\jobs\ExportBatchJob;

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

        $query = new Query();
        $query->from("{{%queue}}")
        ->select('*')
        ->where(['like', 'job', "%{$class}%", false])
        ->andWhere(['like', 'job', "%{$export->id}%", false]);
        
        return $query->one();
    }

    public function getElementTypeSettings($element): array
    {
        return Exporter::getInstance()->elements->getElementTypeSettings($element);
    }

    public function getSubGroupItems($options, $id)
    {
        $object = \Craft::createObject($options['class']);
        $function = $options['function'];
        return $object->$function($id);
    }

    public function getFieldParser(Field $field)
    {
        $parser = Exporter::getInstance()->fields->getParser($field);
        return $parser;

//        if (!$parser) {
//            return false;
//        }
//
//        return [
//            'parser' => get_class($parser),
//            'optionType' => $parser->getOptionType(),
//            'options' => $parser->getOptions(),
//        ];
    }
}
