<?php
return [
    'common' => [
        'before' => [
            \CC\Codebase\Middleware\TokenMiddleware::class, //权限令牌检查中间件
            \CC\Codebase\Middleware\BlackMiddleware::class, //黑名单检查中间件
            \CC\Codebase\Middleware\InputMiddleware::class, //传入参数检查中间件
            \CC\Codebase\Middleware\CommonMiddleware::class, //通用中间件
        ],
        'after' => [
            \CC\Codebase\Middleware\OutputMiddleware::class, //输出格式化
        ],
    ],
    'api' => [
        'before' => [
            \CC\Codebase\Middleware\ApiMiddleware::class,   //api的检查中间件
        ],
        'after' => [
        ],
    ],
    'web' => [
        'before' => [],
        'after' => [],
    ],
];