<?php

use yii\db\Migration;

/**
 * Class m240423_080956_extend_cabinet
 */
class m240423_080956_extend_cabinet extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Yii::$app->db->tablePrefix . 'cabinet', 'show_tickets', $this->tinyInteger(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240423_080956_extend_cabinet cannot be reverted.\n";

        return false;
    }
}
