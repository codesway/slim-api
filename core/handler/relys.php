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
$container['renderer'] = function ($c) {
    $render = $c->configHandler->get('base', 'renderer');
    return new Slim\Views\PhpRenderer($render['template_path']);
};
//print_r($settings = $container->get('configHandler')->get('base')); exit();
// monolog
$container['logger'] = function ($c) {
    $log = $c->get('configHandler')->get('base', 'logger');
    $logger = new Monolog\Logger($log['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($log['path'], $log['level']));
    return $logger;
};


// db 1
$container['cc_common'] = function ($c) {
    $dbConf = $c->get('configHandler')->get('database', 'dbs');
    $dbPool = $c->get('configHandler')->get('database', 'pool')[$dbConf];
    $dbInstance = new \Illuminate\Database\Capsule\Manager();
    $dbInstance->addConnection($dbPool);
    $dbInstance->setAsGlobal();
    $dbInstance->bootEloquent();
    return $dbInstance;
};
// db 2
$container['cc_city'] = function ($c) {
    $dbConf = $c->get('configHandler')->get('database', 'dbs');
    $current = 'callcenter';
    $dbPool = $c->get('configHandler')->get('database', 'pool')[$current];
    $dbInstance = new \Illuminate\Database\Capsule\Manager();
    $dbInstance->addConnection($dbPool);
    $dbInstance->setAsGlobal();
    $dbInstance->bootEloquent();
    return $dbInstance;
};