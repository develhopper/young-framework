<?php
namespace Young\Framework\Http;

class Response{
    private $controller;
    private $method;
    private $params;
    public function __set($var,$value){
        $this->$var=$value;
    }
    public function send(){
        $this->controller=new $this->controller;
        call_user_func_array([$this->controller,$this->method],$this->params);
    }
}