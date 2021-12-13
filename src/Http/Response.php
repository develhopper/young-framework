<?php
namespace Young\Framework\Http;

use Exception;
use Young\Framework\Exceptions\Exception as ExceptionsException;

class Response{
    const JSON="application/json";
    const HTML="text/html";
    const TEXT="text/plain";
    
    private $type="text/html";
    private $code=200;
    private $content = "";

    public function __construct($content="",$type=self::HTML,$code=200)
    {
        $this->content = $content;
        $this->type = $type;
        $this->code = $code;
    }
    public function __set($var,$value){
        $this->$var=$value;
    }

    public function handle(){

    }

    public function send(){
        header('Content-Type: '.$this->type);
        $result = $this->resolve();
        http_response_code($this->code);
        echo $result;
        exit;
    }

    private function resolve(){
        if($this->content instanceof Exception){
            if($this->content instanceof ExceptionsException){
                $this->code == $this->content->code;
            }else{
                $this->code = 500;
            }

            if(getenv('DEBUG')){
                return "<pre style='color:red'>".$this->content->__toString()."</pre>";
            }else{
                return $this->content->getMessage();
            }
        }
        else if(is_string($this->content)){
            return $this->content;
        }else if(is_array($this->content)){
            return json($this->content,200);
        }
    }
}