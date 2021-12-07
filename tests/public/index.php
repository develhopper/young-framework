<?php

use Young\Framework\Http\Request;
use Young\Framework\Kernel;

require_once __DIR__ . "/../../vendor/autoload.php";

// use Denver\Env;

// Env::setup(__DIR__."/.env");

define("BASEURL", $_SERVER['SERVER_NAME']);
define("BASEDIR",realpath(__DIR__."/.."));


spl_autoload_register(function ($name) {
	$filename = BASEDIR . DIRECTORY_SEPARATOR . str_replace('\\', '/', $name) . '.php';
	if (file_exists($filename)) {
		require_once $filename;
	} else {
		if(isset($_SERVER['SERVER_PROTOCOL']))
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		echo "Class '$filename' Not Found";
		exit;
	}
});

$kernel = new Kernel(BASEDIR);

$response = $kernel->handle(new Request());

$response->send();