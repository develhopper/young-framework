<?php
namespace Young\Framework;;

use Young\Framework\Http\Request;
use Young\Framework\Http\Response;
use Young\Framework\Router\Router;
use Young\Modules\Validation\Validator;
use Primal\Primal;
use Young\Framework\Utils\Reflector;

class Kernel{
    private $router;
    private $route;
    private $middlewares;
    public static $config;

    public function __construct()
    {
        session_start();
        
        $this->load_configs($_ENV['BASE_DIR']);

        $this->load_functions();
        
        $this->init_modules();
        $this->load_router();
    }

    public function load_configs($base_path){
        if(!file_exists($base_path."/app/Kernel.php"))
            throw new Exception("Config file is missing");
        
        self::$config = include $base_path . "/app/Kernel.php";    
    }

    public function load_functions(){
        if(isset(self::$config['global_function_files'])){
            foreach(self::$config['global_function_files'] as $file){
                if(file_exists($file)){
                    require_once $file;
                }
            }
        }
        require_once __DIR__."/Utils/global_functions.php";
    }

    public function init_modules(){
        $primal_options = ['views_dir'=> $_ENV['VIEWS_DIR'],
        'cache_dir'=> $_ENV['CACHE_DIR'],'nodes' => config("primal.nodes")];
        Primal::getInstance($primal_options);
        if(isset(self::$config['validation_rules'])){
            $validator = Validator::getInstance();
            $validator->load(self::$config['validation_rules']);
        }
    }

    public function load_router(){
        $this->middlewares = self::$config['middlewares'];
        $this->router = Router::getInstance();
        if(isset(self::$config['routes'])){
            foreach(self::$config['routes'] as $route => $options){
                $this->router->register($_ENV['BASE_DIR']."/routes/$route",$options);
            }
        }
    }

    public function handle(Request $request){
        try{
            $this->route = $this->router->find($request->url);
            
            $this->run_middlewares($request);
            
            $reflector = $this->init_reflector($request);

            
            $response=new Response();

            $content = $reflector->invoke();

            if(!$content){
                $message = "<br>Invalid return type in {$reflector->class}::{$reflector->method}()<br>";
                throw new \Exception($message,500);
            }
            if($content instanceof Response){
                return $content;
            }
            else{
                $response->content=$content;
            }
            return $response;
        }catch(\Exception $e){
            $response = new Response();
            $response->content = $e;
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
           $reflector = new Reflector();

           $namespace = config("namespaces.controllers");

           $reflector->class = $namespace."\\".$this->route['controller'];
           $reflector->method = $this->route['function'];
           $reflector->parameters = $this->get_url_parameteres($request);
           $reflector->request = $request;
           return $reflector;
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