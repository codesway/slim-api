<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// 所有中间件

$app = Cc\Core\Main::getApp(PHP_SAPI);
$container = $app->getContainer();

$middlewareConf = $container->get('configHandler')->get('middleware');
$level = $container['configHandler']::getEnv()['level'];

//$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware($app));
//加载框架通用级别中间件
if (!empty($middlewareConf['common_level']['before'])) {
    loadMiddle($middlewareConf['common_level']['before'], $app);
}
//加载当前模式级别中间件
if (!empty($middlewareConf[$level]['before'])) {
    loadMiddle($middlewareConf[$level]['before'], $app);
}


if (!empty($middlewareConf['common_level']['after'])) {
    loadMiddle($middlewareConf['common_level']['after'], $app);
}

if (!empty($middlewareConf[$level]['after'])) {
    loadMiddle($middlewareConf[$level]['after'], $app);
}

function loadMiddle($middleArr, $app)
{
    if (empty($middleArr)) {
        return false;
    }

    foreach ($middleArr as $middle) {
        $app->add(new $middle());
    }
}

// TODO
// csrf 验证，黑名单，token，权限  四部必须
// 目前只需要这4个中间件

//$app->add(function ($request, $response, $next) {
//    $response->getBody()->write('CSRF_BEFORE' . "\r\n");
//    $response = $next($request, $response);
//    $response->getBody()->write('CSRF_AFTER' . "\r\n");
//    return $response;
//});
//
//
//$app->add(function ($request, $response, $next) {
//    $response->getBody()->write('IP_BLACK_BEFORE' . "\r\n");
//    $response = $next($request, $response);
//    $response->getBody()->write('IP_BLACK_AFTER' . "\r\n");
//    return $response;
//});
//
//
//
//$app->add(function ($request, $response, $next) {
//    $response->getBody()->write('TOKEN_BEFORE' . "\r\n");
//    $response = $next($request, $response);
//    $response->getBody()->write('TOKEN_AFTER' . "\r\n");
//    return $response;
//});
//
//
//
//$app->add(function ($request, $response, $next) {
//    $response->getBody()->write('SSS_BEFORE' . "\r\n");
//    $response = $next($request, $response);
//    $response->getBody()->write('SSS_AFTER' . "\r\n");
//    return $response;
//});
//
//
//
//$app->add(function ($request, $response, $next) {
//    $response->getBody()->write('RESTFUL_BEFORE' . "\r\n");
//    $response = $next($request, $response);
//    $response->getBody()->write('RESTFUL_AFTER' . "\r\n");
//    return $response;
//});