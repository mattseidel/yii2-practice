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
    public const ORDER_STATUS_ERASER = 1;
    public const ORDER_STATUS_PAYED = 2;
    public const ORDER_STATUS_PACK_OFF = 3;
    public const ORDER_STATUS_DELIVERED = 4;
    public const ORDER_STATUS_CANCELLED = 5;

    const SCENARIO_UPDATE_STATUS = 'update_status';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update_status'] =  ['status'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client'], 'required'],
            [['client', 'seller', 'deliver', 'total'], 'integer'],
            [['date'], 'safe'],
            [['address'], 'string', 'max' => 250],
            [['client'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['client' => 'id']],
            [['deliver'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['deliver' => 'id']],
            [['seller'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['seller' => 'id']],
            ['status', 'required'],
            ['status', 'in', 'range' => [
                self::ORDER_STATUS_ERASER,
                self::ORDER_STATUS_PAYED,
                self::ORDER_STATUS_PACK_OFF,
                self::ORDER_STATUS_DELIVERED,
                self::ORDER_STATUS_CANCELLED
            ]],
            ['total', 'default', 'value' => 0],
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
        return OrderItem::find()->where(['order' => $this->id])->all();
    }

    public static function newOrder($user_id, $adress, $total) {
        $order = new Order();
        $order->client = $user_id;
        $order->address = $adress;
        $order->total = $total;
        $order->save();
        return $order;
    }

}
