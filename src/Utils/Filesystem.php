<?php
namespace Young\Framework\Utils;

class Filesystem{

    public function mv($src,$dst,$upload=true){
        $dst = $this->get_destination($dst);
        if($upload){
            return move_uploaded_file($src,$dst);
        }else{
            return rename($src,$dst);
        }
    }

    public function copy($src,$dst){
        $dst = $this->get_destination($dst);
        return copy($src,$dst);
    }

    public function delete($path){
        $path = $this->get_destination($path);
        return unlink($path);
    }

    public function get_destination($dst){
        $storage = explode('/',trim($dst,'/'));
        $base = $storage[0];
        unset($storage[0]);
        
        $path = config("storage.".$base)."/".implode("/",$storage);

        $this->mkdir(dirname($path));
        return $path;
    }

    public function mkdir($path){
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
    }

}