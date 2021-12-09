<?php

use Young\Framework\Http\Session;

if(!function_exists('asset')){
    function asset($name){
        return $_ENV['BASE_URL']."/public/".$name;
    }
}

if(!function_exists('_e')){
    function _e($in){
        return htmlspecialchars($in);
    }
}

if(!function_exists('csrf_field')){
    function csrf_field(){
        $token=bin2hex(random_bytes(10));
        echo "<input name='csrf' value='$token' type='hidden'>";
    }
}

if(!function_exists('csrf_token')){
    function csrf_token(){
        echo bin2hex(random_bytes(10));
    }
}

if(!function_exists('redirect')){
    function redirect($route){
        if($route=="back"){
            header("Location:javascrtipt://history.go(-1)");
            exit;
        }
        header("Location: ".BASEURL."$route");
    }   
}

if(!function_exists('slug')){
    function slug($var){
        return strtolower(preg_replace("/\s/","_",$var));
    }   
}

if(!function_exists('session')){   
    function session(){
        return new Session;
    }
}

if(!function_exists('die_dump')){   
    function die_dump(...$var){
        var_dump($var);
        die;
    }
}