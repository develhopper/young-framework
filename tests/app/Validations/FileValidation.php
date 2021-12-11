<?php
namespace app\Validations;

use Young\Modules\Validation\ValidationRule;

class FileValidation extends ValidationRule{
    public function validate($input,$arg){
        if(isset($input['size']) && $input['error'] == UPLOAD_ERR_OK){
            return true;
        }
        return false;
    }
}