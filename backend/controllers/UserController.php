<?php

namespace backend\controllers;

use backend\models\Order;
use backend\models\OrderItem;
use backend\models\User;
use yii\base\BaseObject;
use yii\behaviors\TimestampBehavior;
use yii\rest\ActiveController;

class UserController extends ActiveController
{

    public $modelClass = 'backend\models\user';

    /**
     * Funcion para guardar un nuevo pedido
     * @param $user_id
     * @param $item_id
     * @param $quanty
     * @return OrderItem|mixed|string
     */
    public function actionSaveOrder($user_id, $item_id, $quanty, $adress = null, $total = null) {

        $user = User::findOne($user_id);
        $orders = $user->getOrders()->all();

        if ($orders){//Si el cliente tiene pedidos
            $lastOrder = $orders[count($orders)-1];
            if( $lastOrder->status == 1 ){//Si esta en estado borrador
                return $neworder = $this->newOrderItem($lastOrder->id, $item_id, $quanty);
            }elseif ($lastOrder->status == 2) {// Si el pedido esta en estado pagado, creo un nuevo pedido
                $order = new Order();
                $order->client = $user_id;
                $order->address = $adress;
                $order->total = $total;
                $order->save();
                $this->newOrderItem($lastOrder->id, $item_id, $quanty);//
                return $order;
            }
        }
        return $this->asJson([
            'msg' => 'Este cliente no tiene pedidos'
        ]);
    }

    public function newOrderItem($order_id, $item_id, $quanty) {
        $neworder = new OrderItem();
        $neworder->order = $order_id;
        $neworder->item = $item_id;
        $neworder->quanty = $quanty;
        $neworder->save();
        return $neworder;
    }

}
