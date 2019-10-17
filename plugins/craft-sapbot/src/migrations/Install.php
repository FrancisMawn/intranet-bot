<?php

namespace csps\sapbot\migrations;

use craft\db\Migration;

class Install extends Migration
{
    // Public Methods
    // =========================================================================

    public function safeUp()
    {
        $this->createTables();

        return true;
    }

    public function safeDown()
    {
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    protected function createTables()
    {
        $this->createTable('{{%sapbot_unmatchedquery}}', [
            'id'             => $this->primaryKey(),
            'conversationId' => $this->string(),
            'source'         => $this->text(),
            'payload'        => $this->json(),

            'dateCreated'    => $this->dateTime()->notNull(),
            'dateUpdated'    => $this->dateTime()->notNull(),
            'uid'            => $this->uid(),
        ]);
    }

    protected function removeTables()
    {
        $this->dropTableIfExists('{{%sapbot_unmatchedquery}}');
    }
}
