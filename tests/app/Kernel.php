<?php

return $config = [
    "middlewares" => [
        "csrf" => app\Http\Middlewares\CsrfMiddleware::class,
        "test" => app\Http\Middlewares\TestMiddleware::class
    ],
    "routes" => [
        "web.php" => ["middlewares" => "csrf,test"], //Separete middlewares with , sign
        "api.php" => ["prefix" =>"api/"]
    ]
];