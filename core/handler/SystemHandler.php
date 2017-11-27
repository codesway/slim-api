<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/27
 * Time: 15:14
 */


namespace CC\Core\handler;
use CC\Core\Base\EHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Default Slim application error handler
 *
 * It outputs the error message and diagnostic information in either JSON, XML,
 * or HTML based on the Accept header.
 */
class SystemHandler extends EHandler
{

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Throwable $error)
    {
        return parent::_invoke($request, $response, $error);
    }
}
