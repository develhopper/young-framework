<?php
namespace app\Http\controllers;

use app\Http\Models\Country;
use Young\Framework\Exceptions\Exception;
use Young\Framework\Http\Controller;
use Young\Framework\Http\Request;
use Young\Modules\Validation\Validator;

class TestController extends Controller{
    public function index(Request $request){
        $page = ($request->page)?$request->page:1;
        $perpage = ($request->per_page)?$request->per_page:10;

        $countries = (new Country())->select()->paginate($page,$perpage)->getArray();;
        return $this->view("index.html", ["title" => "List of countries", "countries" => $countries]);
    }

    public function get($code){
        $country = (new Country())->select()->where("Code", $code)->first();
        if(!$country){
            throw new Exception("404 Not Found", 404);
        }
        return $this->view("country.html", ["title" => $country->Name, "country" => $country, "cities" => $country->cities()]);
    }

    public function global_functions(){
        echo "<br>";
        asset();
        echo "<br>";
        myFunction();
    }

    public function register(Request $request){
        $validator = Validator::getInstance();

        $rules = [
            "username" => "required,min:4",
            "password" => "required,min:8",
            "email" => "required,email",
            "age" => "number,min:18,max:100"
        ];

        if($validator->validate($request->all(),$rules)){
            return $this->json(["message" => "valid"],200);
        }else{
            return $this->json($validator->messages,400);
        }
    }
}