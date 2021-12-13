<?php

use Young\Framework\Http\Request;
use Young\Framework\Kernel;
use Young\Framework\Utils\Bootstrap;

require_once __DIR__ . "/../../vendor/autoload.php";

$base_dir = realpath(__DIR__."/../");

Bootstrap::bootstrap($base_dir);

$kernel = new Kernel();

$response = $kernel->handle(new Request());

$response->send();