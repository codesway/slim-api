<?php

return [
    // user:args_err|系统错误[%s]    user:隶属模块, args_err: 错误标识，|系统错误：中文提示
    // 用法  throw new \CException (1001, 替换1， 替换2)
    'error_code' => [
        //和系统错误级别完全区分开，启用十万位开始
        /* api错误  100000段*/
        100001 => 'user:args_err|系统 [%s] 错误 [%s]',

        /* orm模块错误 200000段*/
        200001 => 'user:args_err|系统错误 [%s]',
    ],
];