<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchase_return}}`.
 */
class m251125_161426_create_purchase_return_table extends Migration
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

        $this->createTable('{{%purchase_return}}', [
            'id' => $this->primaryKey()->unsigned(),
            'purchase_id' => $this->integer()->unsigned()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'unit_cost' => $this->decimal(10, 2)->notNull(),
            'total_cost' => $this->decimal(10, 2)->notNull(),
            'reason' => $this->text(),
            'returned_at' => $this->dateTime()->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_purchase_return_purchase', '{{%purchase_return}}', 'purchase_id');

        $this->addForeignKey(
            'fk_purchase_return_purchase',
            '{{%purchase_return}}',
            'purchase_id',
            '{{%purchase}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_purchase_return_purchase', '{{%purchase_return}}');
        $this->dropIndex('idx_purchase_return_purchase', '{{%purchase_return}}');
        $this->dropTable('{{%purchase_return}}');
    }

}
