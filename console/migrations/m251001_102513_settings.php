<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m251001_102513_settings
 */
class m251001_102513_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id'                    => Schema::TYPE_PK,
            'key'                   => Schema::TYPE_STRING,
            'value'                 => Schema::TYPE_TEXT,
            'created_at'            => Schema::TYPE_INTEGER,
            'updated_at'            => Schema::TYPE_INTEGER,
        ]);

        $this->batchInsert(
            '{{%settings}}',
            ['key', 'value'],
            [
                ['app_name', 'Сервис экранов'],
                ['rnova_api_url', 'https://app.rnova.org/api/public/'],
                ['rnova_api_key', ''],
                ['rnova_webhook_key', ''],
                ['socket_host', ''],
                ['socket_port', ''],
                ['socket_url', ''],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
