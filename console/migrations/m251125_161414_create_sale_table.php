<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sale}}`.
 */
class m251125_161414_create_sale_table extends Migration
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

        $this->createTable('{{%sale}}', [
            'id' => $this->primaryKey()->unsigned(),
            'total_amount' => $this->decimal(10, 2)->notNull(),
            'total_cost' => $this->decimal(10, 2)->notNull(),
            'note' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()
                ->notNull()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_sale_created_at', '{{%sale}}', 'created_at');
    }

    public function safeDown()
    {
        $this->dropIndex('idx_sale_created_at', '{{%sale}}');
        $this->dropTable('{{%sale}}');
    }

}
