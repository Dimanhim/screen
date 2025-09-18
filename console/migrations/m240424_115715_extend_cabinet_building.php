<?php

use yii\db\Migration;

/**
 * Class m240424_115715_extend_cabinet_building
 */
class m240424_115715_extend_cabinet_building extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = 'ALTER TABLE ' . Yii::$app->db->tablePrefix . 'cabinet ADD building_id INT NULL DEFAULT NULL AFTER clinic_id';
        Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240424_115715_extend_cabinet_building cannot be reverted.\n";

        return false;
    }
}
