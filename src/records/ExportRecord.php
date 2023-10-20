<?php

namespace studioespresso\exporter\records;

use craft\db\ActiveRecord;

class ExportRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%exporter_exports}}';
    }
}
