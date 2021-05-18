<?php

namespace backend\controllers;

use app\environment\messageToDisplay;
use app\models\Order;
use app\models\User;
use yii\rest\ActiveController;

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
        $model = Order::find()->select('date', 'total', 'status')->where(['status' => 1])->all();
        return messageToDisplay::emptyMessage($model);
    }

    public function actionCloseOrder($idOrder)
    {
        
    }
}
