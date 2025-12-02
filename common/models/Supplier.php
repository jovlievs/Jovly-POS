<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string|null $note
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Purchase[] $purchases
 */
class Supplier extends ActiveRecord
{
    public static function tableName()
    {
        return 'supplier';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['note'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 64],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function() {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'name'       => 'Name',
            'phone'      => 'Phone',
            'note'       => 'Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getPurchases()
    {
        return $this->hasMany(Purchase::class, ['supplier_id' => 'id']);
    }
}
