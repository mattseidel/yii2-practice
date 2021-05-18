<?php

namespace backend\controllers;

use backend\models\Order;
use backend\models\OrderItem;
use backend\models\User;
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
        if (!$user) {
            return $this->asJson([
                'ok' => false,
                'msg' => 'Este cliente no existe'
            ]);
        }

        $orders = $user->getOrders()->all();
        
        if ( $orders ) {
            $lastOrder = $orders[count($orders) - 1];
            if($lastOrder->status === 1){//Si esta en estado borrador pues sigo agregando al mismo pedido
                $orderItem = OrderItem::newOrderItem($lastOrder->id, $item_id, $quanty);
            }else{//Si esta en estado pagado, agrego una nueva orden con sus respectivos items
                $newOrder = Order::newOrder($user_id, $adress, $total);
                $orderItem = OrderItem::newOrderItem($newOrder->id, $item_id, $quanty);
            }
        } else{// Si el pedido esta en estado pagado o si el cliente NO tiene pedidos, creo un nuevo pedido
            $newOrder = Order::newOrder($user_id, $adress, $total);
            $orderItem = OrderItem::newOrderItem($newOrder->id, $item_id, $quanty);
        }

        $jsonOrder = isset($lastOrder) ? $lastOrder : $newOrder;
        return $this->asJson([
            'ok' => true,
            'msg' => [
                'user' => $user,
                'order' => $jsonOrder,
                'orderItem' => $orderItem
            ]
        ]);
    }

}
