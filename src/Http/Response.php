<?php
namespace Young\Framework\Http;

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
        http_response_code($this->code);
        echo $this->content;
        exit;
    }
}