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

        if ($callable instanceof \Closure) {
            return call_user_func($callable, $request, $response, $routeArguments);
        }

        if (is_array($callable) && ($callable[0] instanceof \CC\Core\Base\ApiBase)) {
            return self::execApi($callable, $request, $response, $routeArguments);
        }

        return call_user_func($callable, $request, $response, $routeArguments);
    }


    private static function execApi($callable, $request, $response, $routeArguments)
    {
        $obj = $callable[0];
        $method = $callable[1];
        $begin = '_begin';
        $once = '_once';
        $after = '_after';

        if (method_exists($obj, '_once') && $obj->onceExecuted === false) {
            $obj->$once($request, $response, $routeArguments);
            $obj->onceExecuted = true;
        }

        if (method_exists($obj, '_begin')) {
            $obj->$begin($request, $response, $routeArguments);
        }

        $return = $obj->$method($request, $response, $routeArguments);

        if (method_exists($obj, '_after')) {
            $obj->$after($request, $response, $routeArguments);
        }

        return $response->withJson([
            'status' => 0,
            'data' => $return
        ]);
    }
}
