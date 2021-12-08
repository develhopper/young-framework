<?php
namespace app\Http\controllers;

use Young\Framework\Http\Controller;

class TestController extends Controller{
    public function index(){
        $this->view("index.html", ["title" => "Page title", "body" => "page body"]);
    }
}