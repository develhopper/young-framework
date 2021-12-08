<?php
namespace app\Http\controllers;

use app\Http\Models\Country;
use Young\Framework\Exceptions\Exception;
use Young\Framework\Http\Controller;
use Young\Framework\Http\Request;

class TestController extends Controller{
    public function index(Request $request){
        $page = ($request->page)?$request->page:1;
        $perpage = ($request->per_page)?$request->per_page:10;

        $countries = (new Country())->select()->paginate($page,$perpage)->getArray();;
        $this->view("index.html", ["title" => "List of countries", "countries" => $countries]);
    }

    public function get($code){
        $country = (new Country())->select()->where("Code", $code)->first();
        if(!$country){
            throw new Exception("404 Not Found", 404);
        }
        $this->view("country.html", ["title" => $country->Name, "country" => $country, "cities" => $country->cities()]);
    }
}