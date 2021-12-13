<?php
namespace Young\Framework\Http;

use Young\Framework\Exceptions\Exception;
use Young\Framework\Http\Requests\FileRequest;
use Young\Framework\Utils\Filesystem;
use Young\Modules\Validation\Validator;

class Request{
    public $url;
    private Validator $validator;
    public function __construct()
    {
        $this->url = trim($_SERVER['REQUEST_URI'],"/");
        $this->validator = Validator::getInstance();
    }

    public function __get($key){
        if(isset($_REQUEST[$key]))
            return $_REQUEST[$key];
    }

    public function __set($key,$value){
        $_REQUEST[$key]=$value;
    }

    public function all($filter = []){
        if($filter){
            $result = [];
            foreach($filter as $key){
                if(isset($_REQUEST[$key])){
                    $result[$key]=$_REQUEST[$key];
                }
            }
            return $result;
        }
        return $_REQUEST;
    }

    public function has($key){
        return isset($_REQUEST[$key]);
    }

    public function isEmpty(){
        return count($_REQUEST)>1?false:true;        
    }

    public function isMethod($method){
        return $method==$this->method()?true:false;
    }

    public function isRoute($route){
        return preg_match('/'.$route.'/',$this->url);
    }

    public function method(){
        $method = $_SERVER['REQUEST_METHOD'];
        if($method =="POST" && $this->has('_method')){
            $method = $this->_method;
        }
        return $method;
    }

    public function rules(){
        return [];
    }

    public function errors(){
        return $this->validator->messages;
    }

    public function valid(){
        return $this->validator->validate($this->all(),$this->rules());
    }

    public function validate($rules){
        return $this->validator->validate($this->all(),$rules);
    }

    public function files($key=null){
        $file_request = new FileRequest();
        if($key == null)
            return $file_request;
        if($key && isset($_FILES[$key])){
            return $file_request->$key;
        }else{
            throw new Exception("Invalid key \$_FILE[$key]");
        }
    }

    // dst: storage_name/path => public/uploads or private/upload
    public function upload($name,$dst){
        $file = $this->files($name)->tmp_name;
        $filesystem = new Filesystem();
        return $filesystem->mv($file,$dst);
    }
}