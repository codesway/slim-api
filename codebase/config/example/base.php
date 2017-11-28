<?php
return [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header

    // Monolog settings
    'logger' => [
        'name' => 'CCenter',
        'path' => isset($_ENV['docker']) ? 'php://stdout' : LOG_ROOT . 'app.log',
        'level' => \Monolog\Logger::DEBUG,
    ],

    'phpErrorHandler' => \CC\Core\Handler\SystemHandler::class, // php错误
    'errorHandler' => \CC\Core\Handler\CommonHandler::class,    // 通用异常处理器
    'notFoundHandler' => \CC\Core\Handler\NfoundHandler::class, // 不存在
    'notAllowedHandler' => \CC\Core\Handler\InvalidHandler::class, //无效(args|func)
    'foundHandler' => \CC\Core\Handler\RequestResponseHandler::class, //无效(args|func)
    'error_jump' => 'xxx.xxx',
];
