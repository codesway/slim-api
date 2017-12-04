<?php

namespace CallCenter\Exlib;

class WebsocketServerHelper
{

    public static $typeMap = [
        'init' => 'account._bind_client',
        'epx' => 'notice.exp',
    ];

    public static function makeData(string $type, array $data)
    {

        if (empty(self::$typeMap[$type])) {
            throw new Exception('websocketServer.u_type_error');
        }

        return json_encode([
            'type' => $type,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
    }
}