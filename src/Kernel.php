<?php
namespace Young\Framework;;

use Young\Framework\Exceptions\Exception;
use Young\Framework\Http\Request;
use Young\Framework\Http\Response;
use Young\Framework\Router\Router;
use Young\Modules\Validation\Validator;
use Denver\Env;

class Kernel{
    private $router;
    private $callback;
    private $controller;
    private $method;
    private $params = [];
    private $route;
    private $request;
    private $middlewares;

    public function __construct(string $base_path)
    {
        session_start();
        include $base_path . "/app/Kernel.php";
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

        if(isset($config['global_function_files'])){
            foreach($config['global_function_files'] as $file){
                if(file_exists($file)){
                    include $file;
                }
            }
        }
        include __DIR__."/Utils/global_functions.php";
        
        if(isset($config['validation_rules'])){
            $validator = Validator::getInstance();
            $validator->load($config['validation_rules']);
        }
    }

    public function handle(Request $request){
        try{
            $this->route = $this->router->find($request->url);
            $this->request=$request;
            $this->runMiddlewares();
            
            $this->setController();

            array_push($this->params,$request);
        
            $response=new Response();

            $content = call_user_func_array($this->callback,$this->params);
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

    private function runMiddlewares(){
        $route_middlewares = explode(',', $this->route['middlewares']);
        foreach($route_middlewares as $middleware){
            if(isset($this->middlewares[$middleware])){
                $m = new $this->middlewares[$middleware];
                $m->handle($this->request);
            }
        }
    }

    private function setController()
    {
        $this->controller = "app\\Http\Controllers\\" . $this->route['controller'];
        $this->method = $this->route['function'];
        $this->setParams();
        $this->callback = [new $this->controller,$this->method];
    }

    private function setParams()
    {
        $url_parts = explode("/", $this->request->url);
        $parts = explode("\/", $this->route['regex']);
        foreach ($parts as $key => $p) {
            if ($p == "(.+)")
                array_push($this->params, $url_parts[$key]);
        }
    }


}