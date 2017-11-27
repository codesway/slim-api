<?php
/*
** Created by lll.
** FileName: CommonMiddleware.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/20
** Brief
*/

namespace CC\Codebase\Middleware;

class InitMiddleware extends \CC\Core\Base\MiddlewareBase
{
    public function __invoke($request, $response, $next)
    {
        //验证
        $clientInfo = $this->getClientInfo($request);
//        print_r($this->getHeaders($request)); exit();
        $this->set('clientInfo', $clientInfo);
        $response->getBody()->write(__METHOD__ . PHP_EOL);
        $response = $next($request, $response);
        return $response;
    }

    private function getClientInfo($request)
    {
        $uriObj = $request->getUri();
        /**
        [method] => GET
        [scheme] => http
        [authority] => cc.l.com
        [user_info] =>
        [host] => cc.l.com
        [port] =>
        [path] => /user/ddd
        [base_path] =>
        [query_string] =>
        [fragment] =>
        [base_url] => http://cc.l.com
         */
        return [
            'method' => $request->getMethod(),
            'scheme' => $uriObj->getScheme(),
            'authority' => $uriObj->getAuthority(),
            'user_info' => $uriObj->getUserInfo(),
            'host' => $uriObj->getHost(),
            'port' => $uriObj->getPort(),
            'path' => $uriObj->getPath(),
            'base_path' => $uriObj->getBasePath(),
            'query_string' => $uriObj->getQuery(),
            'fragment' => $uriObj->getFragment(),
            'base_url' => $uriObj->getBaseUrl(),
            'request_time' => time(),
            'client_id' => 'xxx',
        ];
    }
    private function getHeaders($request)
    {
//        $headers = $request->getHeaders();
//        foreach ($headers as $name => $values) {
//            echo $name . ": " . implode(", ", $values) . PHP_EOL;
//        }
    }
}

