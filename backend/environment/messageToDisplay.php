<?php

namespace app\environment;

class messageToDisplay
{
    private const MESSAGE_NOT_FOUND = ['message' => 'no information to display', 'code' => 400];

    /**
     * public to return error empty error message or array returning 
     * @param queryResult result from query
     */
    public static function emptyMessage($queryResult)
    {
        if (count($queryResult) === 0)
            return self::MESSAGE_NOT_FOUND;
        return $queryResult;
    }
}
