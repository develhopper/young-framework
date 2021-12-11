<?php
namespace Young\Framework\Http\Requests;

use Young\Framework\Exceptions\Exception;
use Young\Framework\Http\Request;

class FileRequest extends Request{

    private $file;
    
    public function __get($key){
        if(!$this->file){
            $this->file = $_FILES[$key];
            return $this;
        }else{
            if(isset($this->file[$key]))
                return $this->file[$key];
        }
        parent::__get($key);
    }

    public function all(){
        return $_FILES;
    }
}