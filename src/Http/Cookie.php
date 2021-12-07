<?php
namespace Young\Framework\Http;

class Cookie{
    public static function get($key)
    {
        if (static::has($key))
            return $_COOKIE[$key];
    }

    public static function set($key, $value,$expire=3600*24*30)
    {
        setcookie($key,$value,time()+$expire);
    }

    public static function has($key)
    {
        return isset($_COOKIE[$key]);
    }

    public static function remove($key)
    {
        setcookie($key,"",time()-1);
    }
}