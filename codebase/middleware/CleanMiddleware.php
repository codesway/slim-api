<?php
/*
** Created by lll.
** FileName: OutputMiddleware.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/23
** Brief
*/

namespace CC\Codebase\Middleware;

class CleanMiddleware
{
    public function __invoke($request, $response, $next)
    {
        //验证
        $response = $next($request, $response);
//        $response->getBody()->write(__METHOD__ . PHP_EOL);
        return $response;
    }
}