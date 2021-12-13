<?php
namespace Young\Framework\Http;

use Primal\Primal;
class Controller{
    protected $method;
    public function __construct()
    {
        
    }

    public function __get($key){
        return $this->$key;
    }

    public function __set($key,$value){
        $this->$key=$value;
    }
}
