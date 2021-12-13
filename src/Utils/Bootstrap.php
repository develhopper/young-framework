<?php
namespace Young\Framework\Utils;

use Denver\Env;
use Exception;

class Bootstrap{
    public static function bootstrap($base_dir){
        if(file_exists($base_dir."/.env")){
            Env::setup($base_dir."/.env");
        }
        if(!file_exists($base_dir."/app/Kernel.php"))
            throw new Exception("Config file is missing");
        
        $config = include $base_dir . "/app/Kernel.php";
        if(isset($config['environment'])){
            Env::fromArray($config['environment']);
        }
        
        spl_autoload_register(function ($name) use($base_dir) {
            $filename = $base_dir . '/' . str_replace('\\', '/', $name) . '.php';
            if (file_exists($filename)) {
                require_once $filename;
            } else {
                header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                echo "Class '$filename' Not Found";
                exit;
            }
        });
    }
}