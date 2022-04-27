<?php

namespace App\Helpers;

class Functions
{
    public static function bcrypt($password)
    {
        $salted = '$2y$10$G#$%78^&*6$%^$#$#$%';
        return sha1($password . '$' . $salted);
    }

    public static function shortLink($link)
    {
        $short = sha1($link.time());
        return substr($short, 0, 6);       
    }
}