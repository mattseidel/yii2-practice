<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property int|null $item
 * @property int|null $order
 * @property int|null $quanty
 *
 * @property Item $item0
 * @property Order $order0
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

  

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item', 'order', 'quanty', 'price'], 'integer'],
            [['item'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item' => 'id']],
            [['order'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item' => 'Item',
            'order' => 'Order',
            'quanty' => 'Quanty',
        ];
    }

    /**
     * Gets query for [[Item0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return Item::find()->where('id', $this->item)->all();
    }

    /**
     * Gets query for [[Order0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder0()
    {
        return $this->hasOne(Order::className(), ['id' => 'order']);
    }

    /**
     * Funcion para agregar un nuevo order_item
     * @param $order_id
     * @param $item_id
     * @param $quanty
     * @return OrderItem
     */
    public static function newOrderItem($order_id, $item_id, $quanty) {
        $neworder = new OrderItem();
        $neworder->order = $order_id;
        $neworder->item = $item_id;
        $neworder->quanty = $quanty;
        $neworder->save();
        return $neworder;
    }
}
