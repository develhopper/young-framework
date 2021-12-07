<?php
namespace Young\Framework\Exceptions;

use \Exception as ParentException;

class Exception extends ParentException{
    protected $message = 'Unknown Exception';
    protected $code=500;

    public function __construct($message = null,$code = 500){
        $this->message = $message;
        $this->code = $code;
    }

    public function __get($key){
        return $this->$key;
    }
}