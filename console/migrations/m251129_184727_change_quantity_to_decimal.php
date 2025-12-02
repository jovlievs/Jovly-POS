<?php

use yii\db\Migration;

class m251129_184727_change_quantity_to_decimal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('purchase', 'quantity', $this->decimal(10,2)->notNull());
        $this->alterColumn('purchase_return', 'quantity', $this->decimal(10,2)->notNull());
        $this->alterColumn('sale_item', 'quantity', $this->decimal(10,2)->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('purchase', 'quantity', $this->integer()->notNull());
        $this->alterColumn('purchase_return', 'quantity', $this->integer()->notNull());
        $this->alterColumn('sale_item', 'quantity', $this->integer()->notNull());
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251129_184727_change_quantity_to_decimal cannot be reverted.\n";

        return false;
    }
    */
}
