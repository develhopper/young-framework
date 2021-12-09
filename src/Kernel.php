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
            $this->Routemiddleware();
        }catch(Exception $e){
            echo $e->getMessage();
            return;
        }
        
        $this->setController();
        array_push($this->params,$request);
        $response=new Response();
        $response->controller=$this->controller;
        $response->method=$this->method;
        $response->params=$this->params;
        $response->route=$this->route;
        return $response;
    }

    private function Routemiddleware(){
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