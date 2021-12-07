<?php
namespace app\Http\Middlewares;

use Young\Framework\Http\Middleware;

class TestMiddleware extends Middleware{
    public function handle($request){
        echo "Hello from Test middleware";
    }
}