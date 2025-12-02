<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m251125_161400_create_product_table extends Migration
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

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey()->unsigned(),
            'category_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string(255)->notNull(),
            'sku' => $this->string(64)->unique(),
            'barcode' => $this->string(64)->unique(),
            'cost_price' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'sale_price' => $this->decimal(10, 2)->notNull()->defaultValue(0.00),
            'discount' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'stock_quantity' => $this->integer()->notNull()->defaultValue(0),
            'is_active' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_product_category', '{{%product}}', 'category_id');

        $this->addForeignKey(
            'fk_product_category',
            '{{%product}}',
            'category_id',
            '{{%category}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_product_category', '{{%product}}');
        $this->dropIndex('idx_product_category', '{{%product}}');
        $this->dropTable('{{%product}}');
    }

}
