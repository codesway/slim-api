<?php
return [
    'phpErrorHandler' => \CC\Core\Handler\SystemHandler::class, // php错误
    'errorHandler' => \CC\Core\Handler\CommonHandler::class,    // 通用异常处理器
    'notFoundHandler' => \CC\Core\Handler\NfoundHandler::class, // 不存在
    'notAllowedHandler' => \CC\Core\Handler\InvalidHandler::class, //无效(args|func)
    'foundHandler' => \CC\Core\Handler\RequestResponseHandler::class, //无效(args|func)
    'error_jump' => 'xxx.xxx',
];
