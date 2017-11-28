<?php
namespace CC\Core;

use CC\Codebase\Config\ConfigHandler;
use CC\Codebase\ConfigDefine;

class Main
{
    public static $mode = null;
    public static $app = null;

    public static function getApp($mode)
    {
        self::$mode = $mode;
        self::_before();
        if (empty(self::$app)) {
            self::_buildDefine();
            self::_buildInit();
        }
        self::_after();
        return self::$app;
    }

    private static function _buildDefine()
    {
        defined('VENDOR_ROOT') or define('VENDOR_ROOT', ROOT . 'vendor' . DS);
        defined('LOG_ROOT') or define('LOG_ROOT', ROOT . 'logs' . DS);
        defined('TEMP_ROOT') or define('TEMP_ROOT', ROOT . 'temp' . DS);
        defined('TPL_ROOT') or define('TPL_ROOT', ROOT . 'templates' . DS);
        defined('CODEBASE_ROOT') or define('CODEBASE_ROOT', ROOT . 'codebase' . DS);
        defined('CC_ROOT') or define('CC_ROOT', ROOT . 'controller' . DS);
        defined('CONTROLLER_SUFFIX') or define('CONTROLLER_SUFFIX', 'controller');
        defined('ACTION_SUFFIX') or define('ACTION_SUFFIX', 'action');
        defined('C_ROOT') or define('C_ROOT', ROOT . 'controller' . DS);
        defined('A_ROOT') or define('A_ROOT', ROOT . 'api' . DS);
    }

    private static function _buildInit()
    {
//        session_start(); //session暂时默认存
        // 顺序不能变， 自动加载->引入核心->加载项目必备配置
        $loader = require VENDOR_ROOT . 'autoload.php';
        $map = include CORE_ROOT . 'loader/psr4_autoload.php';
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }
//        $loader->register(false);
//        print_r($loader->getPrefixesPsr4());
//        new \CC\Controller\User\UserController();
//        exit('eee');
//        print_r($loader->getPrefixesPsr4()); exit();
//        print_r(new \CC\Codebase\Middleware\TokenMiddleware());
//        print_r(new \CC\Controller\User\UserController());
        //启动核心
        ConfigHandler::init();
        self::$app = new \CC\Core\Base\AppBase(ConfigHandler::get('slim'));//下面的三个顺序不可变，必须是这个顺序
        self::registerErrorHandler();
        //载入di依赖组件
        self::_loadHandler(CORE_ROOT . 'include' . DS . 'relys.php');
        //载入中间件
        self::_loadHandler(CORE_ROOT . 'include' . DS . 'middleware.php');
        //载入路由分发
        self::_loadHandler(CORE_ROOT . 'include' . DS . 'routes.php');

    }

    private static function registerErrorHandler()
    {
        \CC\Core\Base\EHandler::init(self::getDI());
        set_error_handler(['CC\\Core\\Base\\EHandler', 'customErrorHandler']);
        set_exception_handler(['CC\\Core\\Base\\EHandler', 'customExceptionHandler']);
        register_shutdown_function(['CC\\Core\\Base\\EHandler', 'customShutDownHandler']);
    }

    private static function _before()
    {
        return true;
    }

    private static function _after()
    {
        return true;
    }

    private static function _loadHandler($filename)
    {
        include $filename;
    }

    public static function getDI($name = null)
    {
        $di = self::$app->getContainer();
        if ($name === null) {
            return $di;
        }
        return $di->get($name);
    }
}