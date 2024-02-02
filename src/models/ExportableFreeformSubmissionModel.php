<?php

namespace studioespresso\exporter\models;

use Solspace\Freeform\Elements\Submission;
use Solspace\Freeform\Freeform;

class ExportableFreeformSubmissionModel extends ExportableElementTypeModel
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
    public string $elementLabel = "Freeform Submissions";

    /**
     * This function defines the groups in which the element can be groups. Like sections for entries, forms for submissions , etc
     * @return array
     */
    public function getGroup(): array
    {
        return [
            "label" => "Form",
            "parameter" => "formId",
            "items" => Freeform::getInstance()->forms->getAllForms(), // @phpstan-ignore-line
            "nameProperty" => "name",
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
