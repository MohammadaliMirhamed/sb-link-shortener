<?php

namespace App\Helpers;

class Authenticated
{
    /**
     * keep the loggedin user data
     */
    
    protected static $data = [];

    public static function init($data)
    {
        self::$data = $data;
    }

    public static function user()
    {
        return self::$data;
    }
}