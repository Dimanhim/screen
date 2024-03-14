<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240314_110602_user_accesses
 */
class m240314_110602_user_accesses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_accesses}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'user_id'               => Schema::TYPE_INTEGER,
            'access_type'           => Schema::TYPE_STRING,
            'building_id'             => Schema::TYPE_INTEGER,

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
        $this->dropTable('{{%user_accesses}}');
    }
}
