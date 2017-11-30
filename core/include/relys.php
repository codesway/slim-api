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
    $loggerUid->getUid();
    $logger->pushProcessor($loggerUid);
    $logger->pushHandler(new Monolog\Handler\StreamHandler($log['path'], $log['level']));
    return $logger;
};


// register DB
$dbs = $container['configHandler']->get('database', 'dbs');
$connect = $container['configHandler']->get('database', 'pool');
//
//print_r($dbs);
//print_r($connect); exit();

$container['DbMaster'] = function ($c) use ($dbs, $connect) {
    $dbInstance = new \Illuminate\Database\Capsule\Manager();
    foreach ($dbs as $name => $alias) {
        $connect[$alias]['database'] = $name;
        $dbInstance->addConnection($connect[$alias], $name);
//        $dbInstance->setFetchMode(PDO::FETCH_ASSOC);
    }
    $dbInstance->setAsGlobal();
    $dbInstance->bootEloquent();
    return $dbInstance;
};


//foreach ($dbs as $name => $database) {
//    $alias = DB_ALIAS. $name;
//    $conf = $connect[$database];
//    $conf['database'] = $name;
//    $container[$alias] = function ($c) use ($alias, $conf) {
//        $dbInstance = new \Illuminate\Database\Capsule\Manager();
//        \Illuminate\Database\Capsule\Manager::connection($alias);
//        $dbInstance->addConnection($conf, $alias);
//        $dbInstance->setAsGlobal();
//        $dbInstance->bootEloquent();
//        return $dbInstance;
//    };
//}
//
//print_r($dbConf);
//print_r($connect); exit();
//
//
//
//
//// db 1
//$container['commonDB'] = function ($c) {
//    $dbConf = $c->get('configHandler')->get('database', 'dbs');
//    $database = $dbConf['common'];
//    $dbPool = $c->get('configHandler')->get('database', 'pool')[$database];
//    $dbInstance = new \Illuminate\Database\Capsule\Manager();
//    $dbInstance->addConnection($dbPool);
//    $dbInstance->setAsGlobal();
//    $dbInstance->bootEloquent();
//    return $dbInstance;
//};
//// db 2
//$container['bjDB'] = function ($c) {
//    $dbConf = $c->get('configHandler')->get('database', 'dbs');
//    $connect = $c->get('configHandler')->get('database', 'pool');
//    print_r($dbConf); print_r($connect); exit();
//    $key = 'bjDB';
//    $database = $dbConf['bj'];
//    print_r($database); exit();
//    $dbPool = $c->get('configHandler')->get('database', 'pool')[$database];
//    $dbInstance = new \Illuminate\Database\Capsule\Manager();
//    $dbInstance->addConnection($dbPool);
////    $dbInstance->setFetchMode(PDO::FETCH_BOTH);
//    $dbInstance->setAsGlobal();
//    $dbInstance->bootEloquent();
//    return $dbInstance;
//};
//
//
//// testDB
//$container['testDB'] = function ($c) {
//    $dbConf = $c->get('configHandler')->get('database', 'dbs');
//    $dbPool = $c->get('configHandler')->get('database', 'pool')[$dbConf];
//    $dbInstance = new \Illuminate\Database\Capsule\Manager($c);
//    $dbInstance->addConnection($dbPool);
////    $dbInstance->setAsGlobal();
//    $dbInstance->bootEloquent();
//    return $dbInstance;
//};

//接管4个系统默认的异常处理器
//'phpErrorHandler' => \CC\Core\Handler\SystemHandler::class, // php错误
//    'errorHandler' => \CC\Core\Handler\CommonHandler::class,    // 通用异常处理器
//    'notFoundHandler' => \CC\Core\Handler\NfoundHandler::class, // 不存在
//    'notAllowedHandler' => \CC\Core\Handler\InvalidHandler::class, //无效(args|func)
//    'foundHandler' => \CC\Core\Handler\RequestResponseHandler::class, //无效(args|func)
$container['phpErrorHandler'] = function ($c) {
//    $class = $c->get('configHandler')->get('base', 'phpErrorHandler');
    return new \CC\Core\Handler\SystemHandler($c);
};

$container['errorHandler'] = function ($c) {
//    $class = $c->get('configHandler')->get('base', 'errorHandler');
    return new \CC\Core\Handler\CommonHandler($c);
};

$container['notFoundHandler'] = function ($c) {
//    $class = $c->get('configHandler')->get('base', 'notFoundHandler');
    return new \CC\Core\Handler\NfoundHandler($c);
};

$container['notAllowedHandler'] = function ($c) {
//    $class = $c->get('configHandler')->get('base', 'notAllowedHandler');
    return new \CC\Core\Handler\InvalidHandler($c);
};

$container['foundHandler'] = function ($c) {
//    $class = $c->get('configHandler')->get('base', 'foundHandler');
    return new \CC\Core\Handler\RequestResponseHandler();
};