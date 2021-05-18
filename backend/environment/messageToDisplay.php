<?php

namespace app\environment;

use yii\web\Response;

class messageToDisplay
{
    private const MESSAGE_NOT_FOUND = ['message' => 'no information to display', 'code' => 400];
    private const MESSAGE_ID_NOT_FOUND = ['message' => 'id not found, not possible to update', 'code' => 400];

    /**
     * public to return error empty error message or array returning 
     * @param app\environment\model $queryResult result from query
     * @return json $queryResult to display
     */
    public static function emptyMessage($queryResult)
    {
        \yii::$app->response->format = Response::FORMAT_JSON;
        if (count($queryResult) === 0)
            return self::MESSAGE_NOT_FOUND;
        return $queryResult;
    }


    /**
     * function to update a returning the query result
     * @param app\environment\model $queryResult database model you want to update
     * @return json $queryResult to display
     */
    public static function updateReturningQueryResult($queryResult)
    {
        \yii::$app->response->format = Response::FORMAT_JSON;

        $queryResult->update();
        return $queryResult;
    }

    public static function sendIdeNotFoundMessage()
    {
        \yii::$app->response->format = Response::FORMAT_JSON;

        return self::MESSAGE_NOT_FOUND;
    }
}
