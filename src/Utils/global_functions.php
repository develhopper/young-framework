<?php
use Primal\Primal;
use Young\Framework\Exceptions\Exception;
use Young\Framework\Http\Response;
use Young\Framework\Http\Session;
use Young\Framework\Kernel;

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
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
        die;
    }
}

if(!function_exists('config')){
    function config($key){
        $keys = explode(".",$key);
        $result = Kernel::$config;
        foreach($keys as $key){
            if(isset($result[$key])){
                $result = $result[$key];
            }else{
                throw new Exception("Invalid config key $key");
            }
        }

        return $result;
    }
}

if(!function_exists('view')){
    function view($name,$params = []){
        $primal = Primal::getInstance();
        return $primal->view($name,$params);
    }
}

if(!function_exists('json')){
    function json(array $arr,$responseCode=200){
        $json = json_encode($arr,JSON_PRETTY_PRINT);
        return new Response($json,Response::JSON,$responseCode);
    }
}