<?php
/**
 * by:lll
 * php runroot/gateway.php dev start
 */
namespace CallCenter\Exlib;

use Workerman\Worker as Worker;
use GatewayWorker\BusinessWorker as BusinessWorker;
use GatewayWorker\Gateway as GatewayWorker;
use GatewayWorker\Register as Register;
use GatewayWorker\Lib\Gateway as Gateway;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use CallCenter\Exlib\WebsocketServerHelper;

include EXLIB_ROOT . 'LoggerMaster.php';
include EXLIB_ROOT . 'WebsocketServerHelper.php';

final class GatewayWorkerMaster extends \BaseApp
{
    const WORKERMAN_MODE_TOOL = 210;
    const WORKER_ERROR_CODE = 211;
    const WORKER_EXCEPTION_CODE = 212;

    public $params;
    public $conf = [];

    public $debug = true;

    public static $logger = null;

    public function __construct($argv)
    {
        parent::__construct();
        $this->mode = self::WORKERMAN_MODE_TOOL;
        $this->params = $argv;
    }

    protected function _loadConf()
    {
        $workerConf = \Conf::get('gateway.worker.conmmon', []);
        if (empty($workerConf)) {
            exit('worker.conf is empty');
        }
        if (empty($workerConf['server'])) {
            exit('worker.conf.server is empty');
        }
        return $workerConf;
    }

    public function init()
    {
        parent::init();
        $this->conf = $this->_loadConf();
        self::loadInstance();
        $this->_workerReady();
        //加载Cache模块
        require_once LIB_ROOT . 'com/Com.php';
        $conf = \Conf::get('hapn.com');
        //hapn的logger不处理
        if ($this->debug) {
            $conf['log_func'] = 'Logger::debug';
        }
        \Com::init($conf);
    }

    public static function loadInstance()
    {
        if (empty(self::$logger)) {
            self::$logger = LoggerMaster::init('gateway')->set(function ($logger) {
                $logger->pushHandler(new StreamHandler(LOG_ROOT . 'gateway.log', Logger::DEBUG));
                $logger->pushProcessor(function ($record) {
                    $record['extra']['filename'] = __CLASS__;
                    return $record;
                });
            });
        }
    }

    //载入所有需要的服务
    protected function _workerReady()
    {
        //start gateway
        //start register
        //start business gateway
        //start workerman
        $this->_loadGateway($this->conf['server']['gateway']);
        $this->_loadRegister($this->conf['server']['register']);
        $this->_loadBusiness($this->conf['server']['business']);
        $this->_loadWorker($this->conf['server']['worker']);
    }
    //载入注册服务
    protected function _loadRegister($conf)
    {
        if (empty($conf)) {
            \Logger::trace('register.conf:empty');
            exit('register.conf:empty');
        }
        $register = new Register($conf['host'] . ':' . $conf['port']);
        $register->name = $conf['name'];
    }
    //载入gateway
    protected function _loadGateway($conf)
    {
        if (empty($conf)) {
            \Logger::trace('register.conf:empty');
            exit('register.conf:empty');
        }
        // gateway 进程
        $gateway = new GatewayWorker($conf['host'] . ':' . $conf['port']);
        // 设置名称，方便status时查看
        $gateway->name = $conf['name'];
        // 设置进程数，gateway进程数建议与cpu核数相同
        $gateway->count = $conf['pcount'];
        // 分布式部署时请设置成内网ip（非127.0.0.1）
        $gateway->lanIp = $conf['lan_ip'];
        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口
        $gateway->startPort = $conf['start_port'];

        if (!empty($conf['ping_interval']) && !empty($conf['ping_data'])) {
            // 心跳间隔
            $gateway->pingInterval = $conf['ping_interval'];
//             心跳数据
            $gateway->pingData = $conf['ping_data'];
        }
        // 服务注册地址
        $gateway->registerAddress = $conf['register_address'];
    }
    //载入business
    protected function _loadBusiness($conf)
    {
        if (empty($conf)) {
            \Logger::trace('register.conf:empty');
            exit('register.conf:empty');
        }
        // bussinessWorker 进程
        $worker = new BusinessWorker();
        // worker名称
        $worker->name = $conf['name'];
        // bussinessWorker进程数量
        $worker->count = $conf['pcount'];
        // 服务注册地址
        $worker->registerAddress = $conf['register_address'];
        $worker->eventHandler = $conf['event_handler'];
    }

    protected function _loadWorker($conf)
    {
        if (empty($conf)) {
            \Logger::trace('register.conf:empty');
            exit('register.conf:empty');
        }
        foreach (glob(TOOL_ROOT . 'workers/*.php') as $worker) {
            $className = basename($worker, '.php');
            require_once $worker;
            \Conf::load(CONF_ROOT . strtolower($className) . '.conf.php');
            $worker = new Worker();
            $worker->name = 'workerman_task.' . $className;
            $task = new $className($this, $this->params);
            $className::$logger = self::$logger;
            $worker->onWorkerStart = function ($worker) use ($task) {
                if (method_exists($task, 'start')) {
                    $task->start($worker);
                }
            };
        }
    }

    public function process()
    {
        //后台运行
        if ($this->conf['daemon']) {
            Worker::$daemonize = true;
        }
        //标准输出
        if ($this->conf['std_file']) {
            Worker::$stdoutFile = $this->conf['std_file'];
        }
        if ($this->conf['pid_file']) {
            Worker::$pidFile = $this->conf['pid_file'];
        }

        if ($this->conf['logger_file']) {
            Worker::$logFile = $this->conf['logger_file'];
        }
        // add records to the log
        Worker::runAll();
    }

    public function errorHandler()
    {
        $error = func_get_args();
        if (false === parent::errorHandler($error[0], $error[1], $error[2], $error[3])) {
            return;
        }
        if (true === $this->debug) {
            unset($error[4]);
            echo var_export($error, true);
        }
        exit(self::WORKER_ERROR_CODE);
    }

    public function exceptionHandler($ex)
    {
        parent::exceptionHandler($ex);
        if (true === $this->debug) {
            echo $ex->__toString();
        }
        exit(self::WORKER_EXCEPTION_CODE);
    }

    public function shutdownHandler()
    {
        global $__HapN_appid;
        $basic = array('logid' => $this->appId . '-' . ($__HapN_appid - $this->appId));
        \Logger::addBasic($basic);
        parent::shutdownHandler();
    }

    public static function onConnect($clientId)
    {
        //验证权限之类
        //bind client id
        self::$logger->addInfo('################################# Client onConnect  Success  ClientId:' . $clientId . '#Pid:' . posix_getpid());
        Gateway::sendToClient($clientId, WebsocketServerHelper::makeData('init', [
            'client_id' => $clientId
        ]));
    }

    /**
     * 获取客户端消息，执行
     * @param $clientId
     * @param $message
     * @return bool|void
     * @throws Exception
     *
     * $message = [
     *      'method' => 'rpc|worker|request|api',
     *      'route' => '',
     *      'params' => [],
     *      'output' => [ mode => null|stop|own|other|all|assign , to => []]
     * ];
     */
    public static function onMessage($clientId, $message)
    {
        var_dump($clientId, $message);
        return; //暂时不接受客户端的send
//        $ex = [
//            'method' => 'request',
//            'route' => 'call/task/lists',
//            'params' => [],
//            'output' => [
//                'mode' => 'own',
//            ],
//        ];
//        echo json_encode($ex, JSON_UNESCAPED_UNICODE);
//        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$clientId session:".json_encode($_SESSION). " cookies: {$_COOKIE}  onMessage:".$message."\n";
        $requestBody = json_decode($message, true);
//        print_r($requestBody);
        if (empty($requestBody)) {
            \Logger::trace('client_id:' . $clientId . 'onMessage empty$$$');
            return \Logger::flush();
        }

        if (!isset($requestBody['method']) || !isset($requestBody['route']) || !isset($requestBody['params'])) {
            \Logger::trace('client_id:' . $clientId . 'onMessage args empty$$$');
            return \Logger::flush();
        }

        if (!in_array($requestBody['method'], ['rpc', 'worker', 'request', 'api'])) {
            \Logger::trace('client_id:' . $clientId . 'onMessage route err$$$');
            return \Logger::flush();
        }
        //管理器，所有请求都通过管理器打出去
//        echo 'args success';
        $data = GatewayWorkerManager::init($requestBody['method'], $clientId)->exec($requestBody['route'], $requestBody['params']);
//        print_r($data);
        if (empty($requestBody['output']) || !is_array($requestBody['output']) || !empty($requestBody['output']['mode']) || $requestBody['output']['mode'] === 'stop') {
            \Logger::fatal('******send err by:' . $clientId . 'send params:' . var_export($requestBody, true));
            return \Logger::flush();
        }

        $mode = $requestBody['output']['mode'];
        $to = $requestBody['output']['to'] ?? [];
        switch ($mode) {
            case 'all':
                Gateway::sendToAll($data);
                break;
            case 'own':
                Gateway::sendToCurrentClient($data);
                break;
            case 'group':
                if (empty($to)) {
                    break;
                }
                Gateway::sendToGroup($data, $to);
                break;
            case 'other':
                if (empty($to)) {
                    break;
                }
                if (is_array($to)) {
                    Gateway::sendToAll($data, $to);
                } else {
                    Gateway::sendToClient($to, $data);
                }
                break;
            default:
        }
        \Logger::trace('******send suc by:' . $clientId . 'send params:' . var_export($requestBody, true));
        return \Logger::flush();
        // debug
    }
}
