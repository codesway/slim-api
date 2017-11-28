<?php
/*
** Created by lll.
** FileName: TokenMiddle.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/20
** Brief
*/

namespace CC\Codebase\Middleware;

class TokenMiddleware
{
    public function __invoke($request, $response, $next)
    {
        //验证
        //中间件如果想把内容输出到页面上的话就每次都 往body里追加就好了。 yes，就是这样
//        $response->getBody()->write(__METHOD__ . PHP_EOL);
        $response->getBody();
        $response = $next($request, $response);
        return $response;
    }
}

