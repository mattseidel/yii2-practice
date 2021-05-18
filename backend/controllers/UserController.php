<?php

namespace backend\controllers;

use yii\behaviors\TimestampBehavior;
use yii\rest\ActiveController;

class UserController extends ActiveController
{

    public $modelClass = 'backend\models\user';


//    /**
//     * Funcion para eliminar la acción de index en la api rest
//     * @return array
//     */
//    public function actions() {
//        $actions = parent::actions();
//        unset($actions['index']);
//        return $actions;
//    }

}
