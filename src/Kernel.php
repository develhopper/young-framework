<?php
namespace Young\Framework;;

use Young\Framework\Exceptions\Exception;
use Young\Framework\Http\Request;
use Young\Framework\Http\Response;
use Young\Framework\Router\Router;
use Young\Modules\Validation\Validator;
use Denver\Env;
use Young\Framework\Utils\Reflector;

class Kernel{
    private $router;
    private $callback;
    private $controller;
    private $method;
    private $params = [];
    private $route;
    private $request;
    private $middlewares;
    private $reflector;
    private $config;

    public function __construct(string $base_path)
    {
        session_start();
        
        if(isset($config['global_function_files'])){
            foreach($config['global_function_files'] as $file){
                if(file_exists($file)){
                    include $file;
                }
            }
        }
        include __DIR__."/Utils/global_functions.php";
        
        include $base_path . "/app/Kernel.php";
        
        if(isset($config['kernel'])){
            $this->config = $config['kernel'];
        }
        
        if(file_exists($base_path."/.env")){
            Env::setup($base_path."/.env");
        }
        if(isset($config['environment'])){
            Env::fromArray($config['environment']);
        }
        
        $this->middlewares = $config['middlewares'];
        $this->router = Router::getInstance();
        
        if(isset($config['routes'])){
            foreach($config['routes'] as $route => $options){
                $this->router->register($base_path."/routes/$route",$options);
            }
        }
        
        if(isset($config['validation_rules'])){
            $validator = Validator::getInstance();
            $validator->load($config['validation_rules']);
        }
    }

    public function handle(Request $request){
        try{
            $this->route = $this->router->find($request->url);
            
            $this->run_middlewares($request);
            
            $this->init_reflector($request);

            
            $response=new Response();
            
            $content = $this->reflector->invoke();

            if(!$content){
                $message = "<br>Invalid return type in {$this->controller}::{$this->method}()<br>";
                throw new Exception($message,500);
            }
            if($content instanceof Response){
                return $content;
            }if($content instanceof Exception){
                throw $content;
            }
            else{
                $response->content=$content;
            }
            return $response;
        }catch(Exception $e){
            $response = new Response();
            $response->code = $e->code;
            if(getenv("DEBUG")){
                $response->content = "<pre style='color:red'>".$e->__toString()."</pre>";
            }else{
                $response->content = $e->getMessage();
            }
            return $response;
        }
    }

    private function run_middlewares($request){
        $route_middlewares = explode(',', $this->route['middlewares']);
        foreach($route_middlewares as $middleware){
            if(isset($this->middlewares[$middleware])){
                $m = new $this->middlewares[$middleware];
                $m->handle($request);
            }
        }
    }

    private function init_reflector($request){
           $this->reflector = new Reflector();
           $namespace = $this->config['namespaces']['controllers'];
           $this->reflector->class = $namespace."\\".$this->route['controller'];
           $this->reflector->method = $this->route['function'];
           $this->reflector->parameters = $this->get_url_parameteres($request);
           $this->reflector->request = $request;
    }

    private function get_url_parameteres($request)
    {
        $params = [];
        if(!$request->url)
            return $params;

        // example: api/users/1
        $url_parts = explode("/", $request->url);

        // example: api/users/(.+)
        $regex_parts = explode("\/", $this->route['regex']);
        
        $cursor = 0;
        
        foreach ($regex_parts as $key => $regex) {
            if ($regex == "(.+)"){
                $name = $this->route['parameters'][$cursor];
                $params[$name]=$url_parts[$key];
                $cursor++;
            }
        }
        return $params;
    }

}