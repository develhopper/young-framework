<?php

use Young\Framework\Router\Route;

Route::get("/","TestController@index");
Route::get("test","TestController@index");