<?php
namespace Young\Framework\Utils;

use ReflectionClass;
use ReflectionParameter;
use Young\Framework\Exceptions\Exception;
use Young\Framework\Exceptions\HttpException;
use Young\Framework\Http\Model;
use Young\Framework\Http\Request;

class Reflector{
    public string $class;
    public string $method;
    public array $parameters;
    public Request $request;
    private $callable;
    public function __construct(string $class = null,string $method = null,array $parameters = [],Request $request = null)
    {
        if($class)
            $this->class = $class;
        if($method)
            $this->method = $method;
        
        if($parameters)
            $this->parameters = $parameters;
        
        if($request)
            $this->request = $request;
    }

    public function invoke(){
        $args = $this->init_object();
        if($this->callable){
            return call_user_func_array($this->callable, $args);
        }
    }

    private function init_object(){
        $reflection = new ReflectionClass($this->class);
        
        $method = $reflection->getMethod($this->method);
        
        $method_params_list = $method->getParameters();
        $method_args = [];

        foreach($method_params_list as $key => $parameter){
            $method_args[$key] = $this->resolve_parameter($parameter);
        }

        $this->callable = [new $this->class,$this->method];
        return $method_args;
    }

    private function resolve_parameter(ReflectionParameter $parameter){
        $parameter_name = $parameter->getName();

        if($parameter->hasType()){
            $reflection = new ReflectionClass($parameter->getType()->getName());
            if($reflection->isInstance($this->request)){
                return $this->request;
            }
            else if($reflection->isSubclassOf(Request::class)){
                return new ($parameter->getType()->getName());
            }
            else if($reflection->isSubclassOf(Model::class)){
                if(!isset($this->parameters[$parameter_name])){
                    $message = "Invalid parameter name $parameter_name";
                    throw new Exception($message);
                }
                $model = new ($parameter->getType()->getName());
                $key = $this->parameters[$parameter_name];
                $result = $model->find($key);
                if(!$result)
                    throw new HttpException("404 Not found", 404);
                return $result;
            }
        }
        if(!isset($this->parameters[$parameter_name])){
            $message = "Invalid parameter name $parameter_name";
            throw new Exception($message);
        }
        return $this->parameters[$parameter->getName()];
    }
}