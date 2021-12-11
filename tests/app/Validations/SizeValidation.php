<?php
namespace app\Validations;

use Young\Framework\Exceptions\Exception;
use Young\Modules\Validation\ValidationRule;

class SizeValidation extends ValidationRule{
    public const Sizes = [
        "byte" => ["size"=>1,"name" =>"byte"],
        "kb" => ["size"=>1024,"name"=>"kilobytes"],
        "mb" => ["size"=>1024**2, "name"=>"megabytes"], //pow(1024,2)
        "gb" => ["size"=>1024**3, "name"=>"gigabytes"] // pow(1024,3)
    ];
    public function validate($input, $arg){
        preg_match("/(byte|kb|mb|gb)/i",$arg,$unit);
        preg_match("/\d+/",$arg,$size);
        if(!$unit){
            throw new Exception("Invalid unit name :$arg");
        }
        if(!$size){
            throw new Exception("Invalid size parameter $arg");
        }

        $unit = self::Sizes[strtolower($unit[0])];

        $max_size = $size[0]*$unit['size'];

        if($input['size'] >= $max_size){
            $this->message = "file size must be less than ".$size[0]." ".$unit['name'];
            return false;
        }
        return true;
    } 
}