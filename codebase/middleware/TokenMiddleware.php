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
        $response->getBody()->write(__METHOD__ . PHP_EOL);
        $response = $next($request, $response);
        return $response;
    }
}

