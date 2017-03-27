<?php

use yii\db\Schema;
use yii\db\Migration;

class m150511_145008_create_comment_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%comment}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'comment' => Schema::TYPE_STRING . ' NOT NULL',
            "`type` ENUM('image','profile','gallery') NOT NULL",
            'object_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'CONSTRAINT `FK_comment_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%comment}}');
    }
 }