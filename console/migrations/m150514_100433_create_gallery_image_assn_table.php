<?php

use yii\db\Schema;
use yii\db\Migration;

class m150514_100433_create_gallery_image_assn_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%gallery_image_assn}}', [
            'gallery_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'image_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'order' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'FOREIGN KEY (gallery_id) REFERENCES gallery (id) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE RESTRICT ON UPDATE CASCADE',
        ]);

        $this->addPrimaryKey('', '{{%gallery_image_assn}}', ['gallery_id', 'image_id']);
        $this->createIndex('gallery_image', '{{%gallery_image_assn}}', ['gallery_id', 'image_id'], true);
        $this->createIndex('gallery_order', '{{%gallery_image_assn}}', ['gallery_id', 'order'], true);
    }

    public function down()
    {
        $this->dropTable('{{%gallery_image_assn}}');
    }
}
