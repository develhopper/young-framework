<?php

namespace Young\Framework\Router;

class Route
{

    private static function add($route, $controller, $method,$middleware)
    {
        $router=Router::getInstance();
        $route=$router->prefix.$route;
        $regex = preg_replace("/\{\w*\}/i", '(.+)', $route);
        $regex = str_replace("/", "\/", $regex);
        
        $parameters= [];
        preg_match_all("/\{(\w*?)\}/",$route,$parameters);
        $controller = explode("@", $controller);
        
        $router->add($method,[
            'controller' => $controller[0],
            'function' => $controller[1],
            'regex' => $regex,
            'method' => $method,
            'middlewares'=>$router->middlewares . $middleware,
            'parameters' => $parameters[1]
        ]);
    }

    public static function get($route, $controller,$middleware=null)
    {
        return self::add($route, $controller, "GET",$middleware);
    }

    public static function post($route, $controller, $middleware = null)
    {
        return self::add($route, $controller, "POST",$middleware);
    }

    public static function put($route, $controller, $middleware=null){
        return self::add($route, $controller, "PUT",$middleware);
    }

    public static function patch($route, $controller, $middleware = null)
    {
        return self::add($route, $controller, "PATCH",$middleware);
    }

    public static function delete($route, $controller, $middleware = null)
    {
        return self::add($route, $controller, "DELETE",$middleware);
    }

    public static function dump(){
        $router=Router::getInstance();
        var_dump($router->routes);
    } 
}
