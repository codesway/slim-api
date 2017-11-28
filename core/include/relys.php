<?php
//所有注册的依赖
// DIC configuration
$app = Cc\Core\Main::getApp(PHP_SAPI);
$container = $app->getContainer();

$container['configHandler'] = function ($c) {
    return CC\Codebase\Config\ConfigHandler::init();
};

//print_r($container->configHandler->get('base')); exit();
// view renderer

//print_r($settings = $container->get('configHandler')->get('base')); exit();
// monolog
$container['logger'] = function ($c) {
    $log = $c->get('configHandler')->get('logger', 'debug');
    $logger = new Monolog\Logger($log['name']);
    $loggerUid = new Monolog\Processor\UidProcessor();
    $logger->pushProcessor($loggerUid->getUid());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($log['path'], $log['level']));
    return $logger;
};

// db 1
$container['commonDB'] = function ($c) {
    $dbConf = $c->get('configHandler')->get('database', 'dbs');
    $dbPool = $c->get('configHandler')->get('database', 'pool')[$dbConf];
    $dbInstance = new \Illuminate\Database\Capsule\Manager();
    $dbInstance->addConnection($dbPool);
    $dbInstance->setAsGlobal();
    $dbInstance->bootEloquent();
    return $dbInstance;
};
// db 2
$container['cityDB'] = function ($c) {
    $dbConf = $c->get('configHandler')->get('database', 'dbs');
    $current = 'callcenter';
    $dbPool = $c->get('configHandler')->get('database', 'pool')[$current];
    $dbInstance = new \Illuminate\Database\Capsule\Manager();
    $dbInstance->addConnection($dbPool);
    $dbInstance->setAsGlobal();
    $dbInstance->bootEloquent();
    return $dbInstance;
};

//接管4个系统默认的异常处理器
$container['phpErrorHandler'] = function ($c) {
    $class = $c->get('configHandler')->get('base', 'phpErrorHandler');
    return new $class($c);
};

$container['errorHandler'] = function ($c) {
    $class = $c->get('configHandler')->get('base', 'errorHandler');
    return new $class($c);
};

$container['notFoundHandler'] = function ($c) {
    $class = $c->get('configHandler')->get('base', 'notFoundHandler');
    return new $class($c);
};

$container['notAllowedHandler'] = function ($c) {
    $class = $c->get('configHandler')->get('base', 'notAllowedHandler');
    return new $class($c);
};

$container['foundHandler'] = function ($c) {
    $class = $c->get('configHandler')->get('base', 'foundHandler');
    return new $class();
};