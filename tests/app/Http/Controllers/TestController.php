<?php
namespace app\Http\controllers;

use app\Http\Models\Country;
use app\Http\Requests\CountryRequest;
use app\Http\Requests\FileRequest;
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

    public function get(Country $country, FileRequest $request){
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

    public function serialize(){
        $model = (new Country)->all();
        return $this->json($model);
    }

    public function new_country(CountryRequest $request){
        if(!$request->valid()){
            var_dump($request->errors());
        }
        return $this->view("new.html");
    }

    public function upload(Request $request){
        $rules = [
            "file" => "required|file|type:jpg,png,gif|size:500kb"
        ];
        if(!$request->files()->validate($rules)){
            return $this->json($request->errors());
        }
        if($request->upload("file","public/uploads/pic.jpg")){
            return $this->json(['message' => 'uploaded']);
        }else{
            return $this->json(['message' => 'error']);
        }
    }
}