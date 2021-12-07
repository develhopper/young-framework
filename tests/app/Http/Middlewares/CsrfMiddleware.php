<?php
namespace app\Http\Middlewares;

use Young\Framework\Exceptions\Exception;
use Young\Framework\Http\Middleware;
use Young\Framework\Http\Session;

class CsrfMiddleware extends Middleware{
    public function handle($request){
        if(in_array($request->method(),["PUT","PATCH","DELETE"])){
            if(!isset($_REQUEST['csrf']))
                throw new Exception("CSRF Token is invalid", 403);
            if(Session::has('csrf')&&Session::get('csrf')==$_REQUEST['csrf']){
                throw new Exception("CSRF Token is invalid", 403);
            }
            else{
                Session::set('csrf',$_REQUEST['csrf']);
            }
        }
    }
}