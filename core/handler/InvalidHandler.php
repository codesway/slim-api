<?php

namespace CC\Core\handler;
use CC\Core\Base\EHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Body;
use UnexpectedValueException;

/**
 * Default Slim application error handler
 *
 * It outputs the error message and diagnostic information in either JSON, XML,
 * or HTML based on the Accept header.
 */
class InvalidHandler extends EHandler
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $methods)
    {
        return parent::_invoke($request, $response, $methods);
    }
}
