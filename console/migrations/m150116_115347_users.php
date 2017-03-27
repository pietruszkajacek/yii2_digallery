<?php

use yii\db\Schema;
use yii\db\Migration;

class m150116_115347_users extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'activation_key', Schema::TYPE_STRING . '(32) AFTER username');
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'activation_key');
    }
}
