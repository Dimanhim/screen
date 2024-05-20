<?php

use yii\db\Migration;

/**
 * Class m240424_143002_extend_user_access_building_id
 */
class m240424_143002_extend_user_access_building_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'ALTER TABLE user_accesses ADD building_id INT NULL DEFAULT NULL AFTER clinic_id';
        Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240424_143002_extend_user_access_building_id cannot be reverted.\n";

        return false;
    }
}
