<?php
namespace app\Http\Requests;

use Young\Framework\Http\Request;

class CountryRequest extends Request{

    public function rules(){
        return [
            "code" => "required,string,min:2",
            "name" => "required,string"
        ];
    }
}