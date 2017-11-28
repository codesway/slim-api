<?php
return [
    'common_level' => [
        'before' => [
            \CC\Codebase\Middleware\TokenMiddleware::class, //权限令牌检查中间件
            \CC\Codebase\Middleware\BlackMiddleware::class, //黑名单检查中间件
            \CC\Codebase\Middleware\InputMiddleware::class, //传入参数检查中间件
            \CC\Codebase\Middleware\InitMiddleware::class, //通用中间件
//            \CC\Codebase\Middleware\ExecuteMiddleware::class, //通用中间件
        ],
        'after' => [
            \CC\Codebase\Middleware\CleanMiddleware::class, //输出格式化
        ],
    ],
    'api_level' => [
        'before' => [
            \CC\Codebase\Middleware\ApiMiddleware::class,   //api的检查中间件
        ],
        'after' => [
        ],
    ],
    'web_level' => [
        'before' => [],
        'after' => [],
    ],
];