<?php

namespace studioespresso\exporter\models;

use craft\base\Model;

/**
 * Exporter settings
 */
class Settings extends Model
{
    /** @var int */
    public $ttr = 300;

    /** @var int */
    public $priority = 1024;
}
