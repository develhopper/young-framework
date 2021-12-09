<?php

return $config = [
    "middlewares" => [
        "csrf" => app\Http\Middlewares\CsrfMiddleware::class,
        "test" => app\Http\Middlewares\TestMiddleware::class
    ],
    "routes" => [
        "web.php" => ["middlewares" => "csrf,test"], //Separete middlewares with , sign
        "api.php" => ["prefix" =>"api/"]
    ],
    "environment" => [
        "BASE_DIR" => realpath(__DIR__."/../"),
        "VIEWS_DIR" => realpath(__DIR__."/../views"),
        "CACHE_DIR" => realpath(__DIR__."/../storage/cache"),
        "BASE_URL" => $_SERVER["SERVER_NAME"]
    ],
    "global_function_files" => [
        __DIR__."/util/global_functions.php"
    ]
];