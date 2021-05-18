<?php

namespace backend\controllers;

use backend\models\Item;
use yii\rest\ActiveController;

class ItemController extends ActiveController
{

    public $modelClass = 'backend\models\item';

    /**
     * Funcion para eliminar la acción de index en la api rest
     * @return array
     */
    public function actions() {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['delete']);
        return $actions;
    }

    /**
     * Obtener los productos disponibles
     * @return array|Item[]|\yii\db\ActiveRecord[]
     */
    public function actionIndex() {
        $items = Item::find()
            ->where(['available' => 1])
            ->all();
        return $items;
    }

    public function actionDelete($id) {
        $item = Item::findOne($id);
        $item->available = 0;
        $item->update();
        return$item;
    }

}
