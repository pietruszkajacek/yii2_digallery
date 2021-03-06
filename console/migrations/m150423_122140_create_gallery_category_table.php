<?php

use yii\db\Schema;
use yii\db\Migration;

class m150423_122140_create_gallery_category_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%gallery_nested_set_category}}', [
            'id' => Schema::TYPE_PK,
            // 'tree' => Schema::TYPE_INTEGER,
            'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
            //'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'short_name' => Schema::TYPE_STRING . ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%gallery_nested_set_category}}');
    }
}
