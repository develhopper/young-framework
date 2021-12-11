<?php

return $config = [
    "namespaces" =>[
            "controllers" => "app\\Http\\Controllers"
    ],
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
    ],
    "validation_rules" => [
        app\Validations\NumberValidation::class,
        app\Validations\FileValidation::class,
        app\Validations\TypeValidation::class,
        app\Validations\SizeValidation::class
    ],
    "storage" => [
        "public" => realpath(__DIR__."/../public/"),
        "private" => realpath(__DIR__."/../storage/")
    ]
];