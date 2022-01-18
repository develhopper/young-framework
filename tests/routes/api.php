<?php

use app\Http\controllers\TestController;
use Young\Framework\Router\Route;

Route::post("register", "TestController@register");
Route::get("countries","TestController@serialize");
Route::post("upload", "TestController@upload");
Route::get('test','TestController@test');
Route::get('.*','TestController@index');