<?php

use yii\db\Schema;
use yii\db\Migration;

class m150320_134421_create_images_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%image}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'can_comment' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 1',
            'can_evaluated' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'plus_18' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'file_name' => Schema::TYPE_STRING . ' NOT NULL',
            'file_type' => Schema::TYPE_STRING . ' NOT NULL',
            'file_ext' => Schema::TYPE_STRING . ' NOT NULL',
            'file_size' => Schema::TYPE_STRING . ' NOT NULL',
            'base_name' => Schema::TYPE_STRING . ' NOT NULL',
            'width' => Schema::TYPE_INTEGER . ' NOT NULL',
            'height' => Schema::TYPE_INTEGER . ' NOT NULL',
            'hidden' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (category_id) REFERENCES image_nested_set_category (id) ON DELETE RESTRICT ON UPDATE CASCADE',            
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%image}}');
    }
}
