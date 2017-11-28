<?php
namespace CC\Core\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

class RequestResponseHandler implements InvocationStrategyInterface
{
    //过滤还未做
    public function __invoke(callable $callable, ServerRequestInterface $request, ResponseInterface $response, array $routeArguments) {
        foreach ($routeArguments as $k => $v) {
            $request = $request->withAttribute($k, $v);
        }
        $obj = $callable[0];
        $method = $callable[1];
        $begin = '_begin';
        $once = '_once';
        $after = '_after';

        $return = '';

        if (method_exists($obj, '_once') && $obj->onceExecuted === false) {
            $return .= $obj->$once($request, $response, $routeArguments);
            $obj->onceExecuted = true;
        }

        if (method_exists($obj, '_begin')) {
            $return .= $obj->$begin($request, $response, $routeArguments);
        }

        $return .= $obj->$method($request, $response, $routeArguments);

        if (method_exists($obj, '_after')) {
            $return .= $obj->$after($request, $response, $routeArguments);
        }
        return $return;
//        $ref = new \ReflectionObject($callable[0]);
//        //$output
//        print_r($callable[0]); exit();
//        $begin = $current = $after = '';
//        if ($ref->hasMethod('_begin')) {
//            $begin = call_user_func([$callable[0], '_begin'], $request, $response, $routeArguments);
//        }
//
//        $current = call_user_func($callable, $request, $response, $routeArguments);
//
//        if ($ref->hasMethod('_after')) {
//            $after = call_user_func([$callable[0], '_after'], $request, $response, $routeArguments);
//        }
//
//        return $begin . $current . $after;
    }
}
