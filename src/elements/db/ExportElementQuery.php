<?php

namespace studioespresso\exporter\elements\db;

use craft\elements\db\ElementQuery;
use studioespresso\exporter\records\ExportRecord;

class ExportElementQuery extends ElementQuery
{

    public function init(): void
    {
        parent::init();
    }

    protected function beforePrepare(): bool
    {
        $elementTable = ExportRecord::tableName();
        $this->joinElementTable($elementTable);
        $this->query->select([
          'exporter_exports.name',
          'exporter_exports.elementType',
          'exporter_exports.settings',
        ]);

        return parent::beforePrepare();
    }
}
