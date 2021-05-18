<?php

namespace backend\controllers;

use app\environment\messageToDisplay;
use app\models\Item;
use app\models\Order;
use app\models\OrderItem;
use app\models\User;
use yii\rest\ActiveController;
use yii\web\Response;

class WorkerController extends ActiveController
{
    public $modelClass = User::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }


    public function actionOrderList()
    {
        $model = Order::find()->select(['date', 'total', 'status'])->where(['status' => Order::ORDER_STATUS_ERASER])->all();
        return messageToDisplay::emptyMessage($model);
    }

    public function actionPayOrder($idOrder)
    {
        $model = Order::find()->where(['status' => Order::ORDER_STATUS_ERASER, 'id' => $idOrder])->one();
        if ($model) {

            $model->status = Order::ORDER_STATUS_PAYED;
            $model->total = $this->calculateTotalAmount($model->getOrderItems());
            return messageToDisplay::updateReturningQueryResult($model);
        } else
            return messageToDisplay::sendIdeNotFoundMessage();
    }

    /**
     * function to calculate total amount for orders
     * @param Array $orderItem order item array from database
     * @return integer total amount
     */
    private function calculateTotalAmount($orderItem)
    {
        $total = 0;
        foreach ($orderItem as $order) {
            $total += $order['price'] * $order['quanty'];
        }
        return $total;
    }

    public function actionDetailOrder($idOrder)
    {
        \yii::$app->response->format = Response::FORMAT_JSON;

        $order = Order::find()->where(['id' => $idOrder])->one();
        $item = $this->getItemsDetails(OrderItem::find()->where(['order' => $idOrder])->all());
        return ['order' => [
            $order,
            'item' => $item
        ]];
    }


    public function actionChangeOrderStatus($idOrder)
    {
        $order = new Order();
        $order->scenario = Order::SCENARIO_UPDATE_STATUS;
        $order->attributes = \Yii::$app->request->post();
        if ($order->validate()) {
            $newOrder = Order::findOne($idOrder);
            $newOrder->status = $order['status'];
            $newOrder->save();
            return ['message' => 'data save successfully', 'code' => 200];
        } else
            return ['status' => false, 'data' => $order->getErrors()];
    }

    /**
     * function to return details from items
     * @param OrderItemModel $items 
     * @return array 
     */
    private function getItemsDetails($items)
    {
        $result = array();
        $datas = Item::find()->all();
        foreach ($items as $item) {
            foreach ($datas as $data) {
                if ($data['id'] === $item['item']) {
                    array_push($result, $this->mergeQuantyToItems($item['quanty'], $data));
                    continue;
                }
            }
        }
        return $result;
    }

    private function mergeQuantyToItems($quanty, $data)
    {
        return  [
            'name' => $data['name'],
            'photo' => $data['photo'],
            'price' => $data['price'],
            'id' => $data['id'],
            'quanty' => $quanty
        ];
    }
}
