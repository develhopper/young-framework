<?php
namespace Young\Framework\Utils;

class Hash{

    public static function make($string){
        $algo = config("hash.algo");
        $options = config("hash.options");
        return password_hash($string,$algo,$options);
    }

    public static function verify($string,$hash){
        return password_verify($string,$hash);
    }
}