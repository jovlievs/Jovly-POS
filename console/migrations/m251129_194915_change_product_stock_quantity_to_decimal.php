<?php

use yii\db\Migration;

class m251129_194915_change_product_stock_quantity_to_decimal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('product', 'stock_quantity', $this->decimal(10,2)->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->alterColumn('product', 'stock_quantity', $this->integer()->notNull()->defaultValue(0));
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251129_194915_change_product_stock_quantity_to_decimal cannot be reverted.\n";

        return false;
    }
    */
}
