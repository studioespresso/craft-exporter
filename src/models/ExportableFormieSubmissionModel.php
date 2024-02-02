<?php

namespace studioespresso\exporter\models;

use verbb\formie\elements\Submission;
use verbb\formie\Formie;

class ExportableFormieSubmissionModel extends ExportableElementTypeModel
{
    /**
     * References the class of the Element Type
     * @phpstan-ignore-next-line
     * @var string
     */
    public $elementType = Submission::class;

    /**
     * Label of the element type
     * @var string
     */
    public string $elementLabel = "Formie Submissions";

    /**
     * This function defines the groups in which the element can be groups. Like sections for entries, forms for submissions , etc
     * @return array
     */
    public function getGroup(): array
    {
        return [
            "label" => "Form",
            "parameter" => "formId",
            "items" => Formie::getInstance()->getForms()->getAllForms(), // @phpstan-ignore-line
            "nameProperty" => "title",
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
