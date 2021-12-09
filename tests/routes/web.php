<?php

use Young\Framework\Router\Route;

Route::get("/","TestController@index");
Route::get("country/{code}", "TestController@get");
Route::get("test_global_functions","TestController@global_functions");