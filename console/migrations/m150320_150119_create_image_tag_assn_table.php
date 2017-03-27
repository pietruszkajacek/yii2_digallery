<?php

use yii\db\Schema;
use yii\db\Migration;

class m150320_150119_create_image_tag_assn_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%image_tag_assn}}', [
            'image_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tag_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'category' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ]);

        $this->addPrimaryKey('', '{{%image_tag_assn}}', ['image_id', 'tag_id']);
    }

    public function down()
    {
        echo "m150320_150119_create_image_tag_assn_table cannot be reverted.\n";

        return false;
    }
}
