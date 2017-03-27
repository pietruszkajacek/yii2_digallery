<?php

use yii\db\Schema;
use yii\db\Migration;

class m150423_131425_create_gallery_tag_assn_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%gallery_tag_assn}}', [
            'gallery_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tag_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'category' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ]);

        $this->addPrimaryKey('', '{{%gallery_tag_assn}}', ['gallery_id', 'tag_id']);
    }

    public function down()
    {
        $this->dropTable('{{%gallery_tag_assn}}');
    }
}
