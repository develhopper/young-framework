<?php

use app\Http\controllers\TestController;
use Young\Framework\Router\Route;

Route::post("register", "TestController@register");
Route::get("countries","TestController@serialize");
Route::get('.*','TestController@index');