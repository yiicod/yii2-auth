<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%User}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            //'role' => Schema::TYPE_STRING . ' NOT NULL',
            'authKey' => Schema::TYPE_STRING . '(32) NOT NULL',
            'passwordHash' => Schema::TYPE_STRING . ' NOT NULL',
            'passwordResetToken' => Schema::TYPE_STRING,
            //'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'createdDate' => Schema::TYPE_DATETIME . ' NOT NULL',
            'updatedDate' => Schema::TYPE_DATETIME . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%User}}');
    }
}
