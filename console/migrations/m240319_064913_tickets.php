<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240319_064913_tickets
 */
class m240319_064913_tickets extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tickets}}', [
            'id'                    => Schema::TYPE_PK,
            'unique_id'             => Schema::TYPE_STRING . ' NOT NULL',

            'clinic_id'             => Schema::TYPE_INTEGER,
            'mis_id'                => Schema::TYPE_STRING,
            'time_start'            => Schema::TYPE_INTEGER,
            'patient_name'          => Schema::TYPE_STRING,
            'appointment_id'        => Schema::TYPE_INTEGER,
            'ticket'                => Schema::TYPE_STRING,

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
        $this->dropTable('{{%tickets}}');
    }
}
