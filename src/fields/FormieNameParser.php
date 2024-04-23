<?php

namespace studioespresso\exporter\fields;

class FormieNameParser extends BaseFieldParser
{
    /**
     * @param $element
     * @param \verbb\formie\fields\formfields\Name $field
     * @return mixed
     * @throws \craft\errors\InvalidFieldException
     */
    public function getValue($element, $field)
    {
        $value = $element->getFieldValue($field['handle']);
        /** @phpstan-ignore-next-line */
        if (is_object($value) && get_class($value) === verbb\formie\models\Name::class) {
            $string = '';
            if ($value->prefix) {
                $string = $string . $value->prefix . " ";
            }
            if ($value->firstName) {
                $string = $string . $value->firstName . " ";
            }
            if ($value->lastName) {
                $string = $string . $value->lastName;
            }
            return $string;
        }
        return $value;
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getOptionType(): string|bool
    {
        return false;
    }

    protected function getOptionLabel(): string|bool
    {
        return false;
    }

    protected function getOptionDescription(): string|bool
    {
        return false;
    }
}
