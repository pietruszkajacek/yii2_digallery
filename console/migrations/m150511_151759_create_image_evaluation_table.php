<?php

use yii\db\Schema;
use yii\db\Migration;

class m150511_151759_create_image_evaluation_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%image_evaluation}}', [
            'id' => Schema::TYPE_PK,
            'image_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'comment_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rate' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'UNIQUE INDEX `images_users_comment` (`image_id`, `user_id`, `comment_id`)',
//            'INDEX `user_id` (`user_id`)',
//            'INDEX `comment_id` (`comment_id`)',
            'CONSTRAINT `FK_image_evaluation_image` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON UPDATE CASCADE ON DELETE CASCADE',
            'CONSTRAINT `FK_image_evaluation_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE',
            'CONSTRAINT `FK_image_evaluation_comment` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`id`) ON UPDATE CASCADE ON DELETE CASCADE',
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%image_evaluation}}');
    }
}