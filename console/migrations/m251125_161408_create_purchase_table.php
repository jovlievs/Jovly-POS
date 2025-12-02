<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchase}}`.
 */
class m251125_161408_create_purchase_table extends Migration
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

        $this->createTable('{{%purchase}}', [
            'id' => $this->primaryKey()->unsigned(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'supplier_id' => $this->integer()->unsigned()->null(),
            'quantity' => $this->integer()->notNull(),
            'unit_cost' => $this->decimal(10, 2)->notNull(),
            'total_cost' => $this->decimal(10, 2)->notNull(),
            'note' => $this->text(),
            'purchased_at' => $this->dateTime()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_purchase_product', '{{%purchase}}', 'product_id');
        $this->createIndex('idx_purchase_supplier', '{{%purchase}}', 'supplier_id');
        $this->createIndex('idx_purchase_purchased_at', '{{%purchase}}', 'purchased_at');

        $this->addForeignKey(
            'fk_purchase_product',
            '{{%purchase}}',
            'product_id',
            '{{%product}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_purchase_supplier',
            '{{%purchase}}',
            'supplier_id',
            '{{%supplier}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_purchase_supplier', '{{%purchase}}');
        $this->dropForeignKey('fk_purchase_product', '{{%purchase}}');

        $this->dropIndex('idx_purchase_purchased_at', '{{%purchase}}');
        $this->dropIndex('idx_purchase_supplier', '{{%purchase}}');
        $this->dropIndex('idx_purchase_product', '{{%purchase}}');

        $this->dropTable('{{%purchase}}');
    }

}
