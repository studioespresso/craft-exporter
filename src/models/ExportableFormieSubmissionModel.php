<?php

namespace studioespresso\exporter\models;

use verbb\formie\elements\Submission;
use verbb\formie\Formie;

class ExportableFormieSubmissionModel extends ExportableElementTypeModel
{
    public $elementType = Submission::class;

    public string $elementLabel = "Formie Submissions";

    public function getGroup(): array
    {
        return [
            "label" => "Form",
            "parameter" => "formId",
            "items" => Formie::getInstance()->getForms()->getAllForms(), // @phpstan-ignore-line
            "nameProperty" => "title",
        ];
    }

    public function getSubGroup(): bool|array
    {
        return false;
    }
}
