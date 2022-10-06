<?php

use yii\db\Migration;

class m221006_201241_create_table_customer extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%customer}}',
            [
                'id' => $this->primaryKey()->unsigned(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'first_name' => $this->string(32)->notNull(),
                'last_name' => $this->string(32)->notNull(),
                'status' => "enum ('active', 'inactive') default 'inactive' not null"
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%customer}}');
    }
}
