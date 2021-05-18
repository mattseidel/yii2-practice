<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $client
 * @property int|null $seller
 * @property int|null $deliver
 * @property string|null $address
 * @property int|null $total
 * @property int|null $status
 * @property string $date
 *
 * @property User $client0
 * @property User $deliver0
 * @property User $seller0
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client'], 'required'],
            [['client', 'seller', 'deliver', 'total', 'status'], 'integer'],
            [['date'], 'safe'],
            [['address'], 'string', 'max' => 250],
            [['client'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['client' => 'id']],
            [['deliver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['deliver' => 'id']],
            [['seller'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['seller' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client' => 'Client',
            'seller' => 'Seller',
            'deliver' => 'Deliver',
            'address' => 'Address',
            'total' => 'Total',
            'status' => 'Status',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[Client0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient0()
    {
        return $this->hasOne(User::className(), ['id' => 'client']);
    }

    /**
     * Gets query for [[Deliver0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliver0()
    {
        return $this->hasOne(User::className(), ['id' => 'deliver']);
    }

    /**
     * Gets query for [[Seller0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeller0()
    {
        return $this->hasOne(User::className(), ['id' => 'seller']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order' => 'id']);
    }
}
