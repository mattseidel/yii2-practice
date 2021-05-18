<?php

namespace backend\controllers;

use backend\models\Order;
use yii\rest\ActiveController;

class OrderController extends ActiveController
{

    public $modelClass = 'backend\models\order';

}
