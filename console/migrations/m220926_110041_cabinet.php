<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220926_110041_cabinet
 */
class m220926_110041_cabinet extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cabinet}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'number'                => Schema::TYPE_STRING,
            'name'                  => Schema::TYPE_STRING . ' NOT NULL',
            'mis_id'                => Schema::TYPE_STRING,

            'is_active'             => Schema::TYPE_SMALLINT,
            'deleted'               => Schema::TYPE_SMALLINT,
            'position'              => Schema::TYPE_INTEGER,
            'created_at'            => Schema::TYPE_INTEGER,
            'updated_at'            => Schema::TYPE_INTEGER,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cabinet}}');
    }
}
