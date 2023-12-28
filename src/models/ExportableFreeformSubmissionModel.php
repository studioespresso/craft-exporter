<?php

namespace studioespresso\exporter\models;

use Solspace\Freeform\Elements\Submission;
use Solspace\Freeform\Freeform;

class ExportableFreeformSubmissionModel extends ExportableElementTypeModel
{
    public $elementType = Submission::class;

    public string $elementLabel = "Freeform Submissions";

    public function getGroup(): array
    {
        return [
            "label" => "Form",
            "parameter" => "formId",
            "items" => Freeform::getInstance()->forms->getAllForms(), // @phpstan-ignore-line
            "nameProperty" => "name",
        ];
    }

    public function getSubGroup(): bool|array
    {
        return false;
    }
}
