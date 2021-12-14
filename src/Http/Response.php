<?php
namespace Young\Framework\Http;

use Exception;
use Young\Framework\Exceptions\HttpException;

class Response{
    const JSON="application/json";
    const HTML="text/html";
    const TEXT="text/plain";
    
    private $type="text/html";
    private $code=200;
    private $content = "";
    private $request;

    public function __construct($content="",$type=self::HTML,$code=200)
    {
        $this->content = $content;
        $this->type = $type;
        $this->code = $code;
        $this->request = new Request;
    }
    public function __set($var,$value){
        $this->$var=$value;
    }

    public function send(){
        $this->resolve_headers();
        $this->content = $this->resolve_content();
        $this->print_content();
    }

    private function resolve_headers(){
        $accept = $this->request->header('accept');
        if($accept == self::JSON)
            $this->type = self::JSON;
    }

    private function resolve_content(){
        if($this->content instanceof Exception){
            if($this->content instanceof HttpException){
                $this->code = $this->content->code;
            }else{
                $this->code = 500;
            }

            if($this->type == self::JSON){
                return [
                    "message" => $this->content->getMessage()
                ];
            }
            if(getenv('DEBUG')){
                return "<pre style='color:red'>".$this->content->__toString()."</pre>";
            }else{
                return $this->content->getMessage();
            }
        }
        else if(is_string($this->content)){
            if($this->type == self::JSON)
                return ["data" => $this->content];
            return $this->content;
        }else if(is_array($this->content)){
            $this->type = self::JSON;
            return $this->content;
        }
    }

    private function print_content(){
        http_response_code($this->code);
        header('Content-Type: '.$this->type);
        if(is_array($this->content)){
            echo json_encode($this->content);
        }else{
            echo $this->content;
        }
        exit;
    }
}