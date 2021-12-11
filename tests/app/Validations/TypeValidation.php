<?php
namespace app\Validations;

use Young\Modules\Validation\ValidationRule;

class TypeValidation extends ValidationRule{

    public function validate($input, $args){
        $this->message = "file extention must be ".implode(',',$args);
        if(isset($input['name'])){
            $ext = pathinfo($input['name'],PATHINFO_EXTENSION);
            return in_array($ext,$args);
        }
        return false;
    }
}