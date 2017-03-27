<?php

use yii\db\Schema;
use yii\db\Migration;

class m150423_140839_create_galleries_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%gallery}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'can_comment' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 1',
            'can_evaluated' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'plus_18' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'hidden' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (category_id) REFERENCES gallery_nested_set_category (id) ON DELETE RESTRICT ON UPDATE CASCADE',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%gallery}}');
    }
}
