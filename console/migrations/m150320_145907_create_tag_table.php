<?php

use yii\db\Schema;
use yii\db\Migration;

class m150320_145907_create_tag_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%tag}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'frequency' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%tag}}');
    }
}
