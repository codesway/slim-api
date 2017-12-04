<?php

return [
    'debug' => [
        'name' => 'CCenter',
        'path' => isset($_ENV['docker']) ? 'php://stdout' : LOG_ROOT . 'app.log',
        'level' => \Monolog\Logger::DEBUG,
    ],
    'warn' => [

    ],
    'fatal' => [

    ],
    'info' => [],
    'notice' => [],
    'critical' => [],
    'alert' => [],
];