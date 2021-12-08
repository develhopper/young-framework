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

    public function view($name,$params=[]){
        $primal=Primal::getInstance(["views_dir"=>getenv('VIEWS_DIR'),
        "cache_dir"=>getenv('CACHE_DIR')]);
        $primal->view($name,$params);
    }

    public function json(array $params,$responseCode){
        header('Content-Type: application/json');
        http_response_code($responseCode);
        echo json_encode($params,JSON_PRETTY_PRINT);
		exit;
    }
    
    public function redirect($route){
        header("Location: $route");
        exit;
    }
}
