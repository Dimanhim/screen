<?php

use yii\db\Migration;

/**
 * Class m240424_130504_extend_building_clinic_id
 */
class m240424_130504_extend_building_clinic_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'ALTER TABLE ' . Yii::$app->db->tablePrefix . 'buildings ADD clinic_id INT NULL DEFAULT NULL AFTER name';
        Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240424_130504_extend_building_clinic_id cannot be reverted.\n";

        return false;
    }
}
