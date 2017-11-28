<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/25
 * Time: 10:41
 */

namespace CC\Codebase\Middleware;


class ExecuteMiddleware extends \CC\Core\Base\MiddlewareBase
{
    public function __invoke($request, $response, $next)
    {
        //方法执行的钩子

        $body = $response->getBody();
//        $response->getBody()->write(__METHOD__ . PHP_EOL);
        $response = $next($request, $response);
        return $response;
    }
}
