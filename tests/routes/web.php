<?php

use Young\Framework\Router\Route;

Route::get("/","TestController@index");
Route::get("/country/{country}", "TestController@get");
Route::get("/add/country", "TestController@new_country");
Route::post("/add/country", "TestController@new_country");
Route::get("/test_global_functions","TestController@global_functions");
Route::get("/hash","TestController@hash");