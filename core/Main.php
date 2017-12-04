<?php
namespace CC\Core;

use CallCenter\Exlib\GatewayWorkerMaster;
use CC\Codebase\Config\ConfigHandler;
use CC\Codebase\ConfigDefine;

class Main
{
    public static $mode = null;
    public static $app = null;
    public static $argv = null;
    public static $di = null;
    public static function getApp($mode, $argv = null)
    {
        if (!empty(self::$app)) {
            return self::$app;
        }
        self::$mode = $mode;
        self::$argv = $argv;
        self::_before();
        self::_buildDefine();
        self::_buildInit();
        self::_after();
        return self::$app;
    }

    private static function _buildDefine()
    {
        defined('VENDOR_ROOT') || define('VENDOR_ROOT', ROOT . 'vendor' . DS);
        defined('LOG_ROOT') || define('LOG_ROOT', ROOT . 'logs' . DS);
        defined('TEMP_ROOT') || define('TEMP_ROOT', ROOT . 'temp' . DS);
        defined('TPL_ROOT') || define('TPL_ROOT', ROOT . 'templates' . DS);
        defined('CODEBASE_ROOT') || define('CODEBASE_ROOT', ROOT . 'codebase' . DS);
        defined('C_ROOT') || define('C_ROOT', ROOT . 'controller' . DS);
        defined('A_ROOT') || define('A_ROOT', ROOT . 'api' . DS);
        defined('SITE_FLAG') || define('SITE_FLAG', 'bj');
        defined('DB_ALIAS') || define('DB_ALIAS', 'DB.');
        defined('LIB_ROOT') || define('LIB_ROOT', CODEBASE_ROOT . 'libs' . DS);
        $loader = require VENDOR_ROOT . 'autoload.php';
        $map = include CORE_ROOT . 'loader/psr4_autoload.php';
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }
        ConfigHandler::init();
    }

    private static function _buildInit()
    {
        //启动核心
        $app = new \Slim\App(['settings' => [
            'displayErrorDetails' => false, // set to false in production
            'addContentLengthHeader' => true, // Allow the web server to send the content-length header
            'determineRouteBeforeAppMiddleware' => true,
        ]]);
        if (self::$mode != 'cli') {
            self::$app = $app;
            self::$di = self::$app->getContainer();
            self::registerErrorHandler();
            //清理掉系统的错误控制，让系统接管
            self::$di->offsetUnset('notFoundHandler');
            self::$di->offsetUnset('notAllowedHandler');

            //下面的三个顺序不可变，必须是这个顺序
            self::$app->status = 'init';
            //载入di依赖组件
            self::_loadHandler(CORE_ROOT . 'include' . DS . 'relys.php');
            //载入中间件
            self::_loadHandler(CORE_ROOT . 'include' . DS . 'middleware.php');
            //载入路由分发
            self::_loadHandler(CORE_ROOT . 'include' . DS . 'routes.php');
        } else {
            // workerman
            self::$app = new GatewayWorkerMaster($app->getContainer(), self::$argv);
            self::registerErrorHandler();
        }
    }

    private static function registerErrorHandler()
    {
        \CC\Core\Base\EHandler::init(self::$di, self::$app);
        \CC\Core\Base\CException::init(self::$di);
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
        self::$app->status = 'load_handler_' . $filename;
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