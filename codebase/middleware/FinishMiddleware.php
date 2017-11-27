<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/25
 * Time: 10:16
 */


namespace CC\Codebase\Middleware;


class FinishMiddleware
{
    public function __invoke($request, $response, $next)
    {
        //验证
        $response->getBody()->write(__METHOD__ . PHP_EOL);
        $response = $next($request, $response);
        return $response;
    }
}
