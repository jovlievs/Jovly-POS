<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sale_item}}`.
 */
class m251125_161420_create_sale_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
        }

        $this->createTable('{{%sale_item}}', [
            'id' => $this->primaryKey()->unsigned(),
            'sale_id' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'unit_price' => $this->decimal(10, 2)->notNull(),
            'unit_cost' => $this->decimal(10, 2)->notNull(),
            'line_total' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_sale_item_sale', '{{%sale_item}}', 'sale_id');
        $this->createIndex('idx_sale_item_product', '{{%sale_item}}', 'product_id');

        $this->addForeignKey(
            'fk_sale_item_sale',
            '{{%sale_item}}',
            'sale_id',
            '{{%sale}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_sale_item_product',
            '{{%sale_item}}',
            'product_id',
            '{{%product}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_sale_item_product', '{{%sale_item}}');
        $this->dropForeignKey('fk_sale_item_sale', '{{%sale_item}}');

        $this->dropIndex('idx_sale_item_product', '{{%sale_item}}');
        $this->dropIndex('idx_sale_item_sale', '{{%sale_item}}');

        $this->dropTable('{{%sale_item}}');
    }

}
