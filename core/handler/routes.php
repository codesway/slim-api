<?php

use Slim\Http\Request;
use Slim\Http\Response;
//所有路由的配置
// Routes
$app = Cc\Core\Main::getApp(PHP_SAPI);

$app->get('/user/ddd', \CC\Controller\User\UserController::class. ':dddAction')->setName('userList');

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return 'this is index response' . PHP_EOL;
});

//第一个版本路由
$app->group('/v1/user', function () {
    $path = explode('/', $this->getContainer()->get('request')->getUri()->getPath());
    $action = array_pop($path);
    $this->get('/' . $action, \CC\Controller\User\UserController::class. ':' . $action . 'Action')->setName('userList');
});

$app->get('/user/ccc', function (Request $request, Response $response, array $args) {
    $obj = $this->cc_city->table('time_st')->get();
    print_r($obj);
})->setName('userInfo');


/**
getScheme()
getAuthority()
getUserInfo()
getHost()
getPort()
getPath()
getBasePath()
getQuery() (返回整个查询字符串，e.g. a=1&b=2)
getFragment()
getBaseUrl()
 */

/*  参考route+url
$app = new \Slim\App();
$app->get('/hello/{name}', function ($request, $response, $args) {
    echo "Hello, " . $args['name'];
})->setName('hello');
You can generate a URL for this named route with the application router’s pathFor() method.

echo $app->getContainer()->get('router')->pathFor('hello', [
    'name' => 'Josh'
]);
// Outputs "/hello/Josh"
*/