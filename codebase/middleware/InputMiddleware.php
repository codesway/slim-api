<?php
/*
** Created by lll.
** FileName: InputMiddleware.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/20
** Brief
*/

namespace CC\Codebase\Middleware;


class InputMiddleware extends \CC\Core\Base\MiddlewareBase
{
    public function __invoke($request, $response, $next)
    {
        //验证
        $this->checkInput($request);
        $response->getBody()->write(__METHOD__ . '检查输入参数等' . PHP_EOL);
        $response = $next($request, $response);
        return $response;
    }

    public function checkInput($request)
    {
        // 获取request的所有参数,定制化检查.提示或者如何
    }
}

