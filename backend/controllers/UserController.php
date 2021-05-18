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
        $orders = $user->getOrders()->all();

        if ($orders){//Si el cliente tiene pedidos
            $lastOrder = $orders[count($orders)-1];
            if( $lastOrder->status == 1 ){//Si esta en estado borrador
                return OrderItem::newOrderItem($lastOrder->id, $item_id, $quanty);
            }elseif ($lastOrder->status == 2) {// Si el pedido esta en estado pagado, creo un nuevo pedido
                Order::newOrder($user_id, $adress, $total);
                return OrderItem::newOrderItem($lastOrder->id, $item_id, $quanty);
            }
        }
        return $this->asJson([
            'msg' => 'Este cliente no tiene pedidos'
        ]);
    }

}
