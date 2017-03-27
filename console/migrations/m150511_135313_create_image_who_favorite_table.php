<?php

use yii\db\Schema;
use yii\db\Migration;

class m150511_135313_create_image_who_favorite_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%image_who_favorite}}', [
            'id' => Schema::TYPE_PK,
            'image_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'UNIQUE INDEX `images_users` (`image_id`, `user_id`)',
//            'INDEX `user_id` (`user_id`)',
//            'INDEX `image_id` (`image_id`)',
            'CONSTRAINT `FK_image_who_favorite_image` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON UPDATE CASCADE',
            'CONSTRAINT `FK_image_who_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%image_who_favorite}}');
    }
}
