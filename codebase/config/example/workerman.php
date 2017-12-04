<?php
$registerAddress = '127.0.0.1:10061';
return [
    'register' => '127.0.0.1:10061',
    'common' => [
        'server' => [
            'gateway' => [
                'host' => 'websocket://0.0.0.0',
                'port' => '10161',  //client链接到这个端口
                'pcount' => 4,
                'lan_ip' => '127.0.0.1',
                'name' => 'gateway.worker',
                'start_port' => 4000,
//            'ping_interval' => 20,
                'ping_data' => '{"ping":"manual"}',
                'register_address' => $registerAddress,
            ],
            'business' => [
                'pcount' => 4,
                'name' => 'business.worker',
                'register_address' => $registerAddress,
                'event_handler' => CallCenter\Exlib\GatewayWorkerMaster::class,
            ],
            'worker' => [
                'pcount' => 4,
                'name' => 'worker'
            ],
        ],
        'daemon' => false,  //守护进程
        'std_file' => TMP_ROOT . 'std.log',
        'pid_file' => TMP_ROOT . 'workerman.pid',
        'logger_file' => LOG_ROOT . 'workerman.log',
    ],
];