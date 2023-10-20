<?php

namespace studioespresso\exporter\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Table;
use studioespresso\exporter\records\ExportRecord;

class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        if ($this->createTables()) {
            $this->addForeignKeys();

            // Refresh the db schema caches
            Craft::$app->getDb()->schema->refresh();
        }

        return true;
    }

    protected function createTables(): bool
    {
        if (!$this->db->tableExists(ExportRecord::tableName())) {
            $this->createTable(ExportRecord::tableName(), [
                'id' => $this->integer()->notNull(),
                'title' => $this->string(),
                'elementType' => $this->string(),
                'settings' => $this->json(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'uid' => $this->uid(),
                'PRIMARY KEY([[id]])',
            ]);
        }
        return true;
    }

    protected function addForeignKeys(): void
    {
        $this->addForeignKey(null, ExportRecord::tableName(), 'id', Table::ELEMENTS, 'id', 'CASCADE');
    }
}
