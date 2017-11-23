<?php
return [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header

    // Renderer settings
    'renderer' => [
//            'template_path' => TPL_ROOT,
    ],

    // Monolog settings
    'logger' => [
        'name' => 'CCenter',
        'path' => isset($_ENV['docker']) ? 'php://stdout' : LOG_ROOT . 'app.log',
        'level' => \Monolog\Logger::DEBUG,
    ],
];
