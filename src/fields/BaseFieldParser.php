<?php

namespace studioespresso\exporter\fields;

use craft\base\Component;
use craft\base\Element;

abstract class BaseFieldParser extends Component
{
    abstract protected function getValue(Element $element, array $field);

    abstract protected function getOptionType(): string|bool;

    abstract protected function getOptionLabel(): string|bool;

    abstract protected function getOptionDescription(): string|bool;

    abstract protected function getOptions(): array;
}
