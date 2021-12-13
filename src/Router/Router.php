<?php
namespace Young\Framework\Router;

use Young\Framework\Exceptions\Exception;
use Young\Framework\Exceptions\HttpException;
use Young\Framework\Http\Request;

class Router{
    private static $INSTANCE = null;
    private Request $request;
    public $routes = [
        "GET" => [],
        "POST" => [],
        "PUT" => [],
        "DELETE" => []
    ];

    private function __construct(){
        $this->request = new Request();
    }

    public static function getInstance(){
        if(self::$INSTANCE==null)
            self::$INSTANCE=new Router();
        return self::$INSTANCE;
    }

    public function register($path,$options=[]){
        $this->applyOptions($options);
        if(file_exists($path)){
            include_once $path;
        }
        else
            die("Router: $path not exists");
    }

    private function applyOptions($options=[]){
        $this->prefix=(isset($options["prefix"]))?$options["prefix"]:"";
        $this->middlewares=(isset($options["middlewares"]))?$options["middlewares"]:"";
    }

    public function add($method,$route){
        array_push($this->routes[$method],$route);
    }

    public function find($name){
        $reqeust_method=$this->request->method();
        
        $name = explode('?',$name)[0];
        
        if (empty($name))
            $name = "/";
        foreach ($this->routes[$reqeust_method] as $r) {
            
            if (preg_match("/^" . $r['regex'] . "$/i", $name) 
            && $this->method_check($r,$reqeust_method)){
                return $r;
            }
        }
        throw new HttpException("Route $name not found",404);
    }

    private function method_check($route, $reqeust_method)
    {
        if($this->request->isMethod($reqeust_method))
            return true;
        else
            throw new Exception("Method not Allowed",405);
    }
}