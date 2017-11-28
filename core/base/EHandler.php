<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/27
 * Time: 10:37
 */
namespace CC\Core\Base;


use Psr\Log\LogLevel;
class EHandler
{
    public static $di = null;

    public static $exceptionMap = [
        'BadFunctionCallException' => ['desc' => '函数不存在或不可调用', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'BadMethodCallException' => ['desc' => '未定义的方法或方法不可调用', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'DomainException' => ['desc' => '不符合有效值的定义区域', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'InvalidArgumentException' => ['desc' => '参数类型错误或参数定义错误', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'LengthException' => ['desc' => '长度无效', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'LogicException' => ['desc' => '逻辑错误', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'OutOfBoundsException' => ['desc' => '无效的值', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'OutOfRangeException' => ['desc' => '非法请求', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'OverflowException' => ['desc' => '数据溢出', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'RangeException' => ['desc' => '不在有效范围', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'RuntimeException' => ['desc' => '运行时的错误', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'UnderflowException' => ['desc' => '操作一个不存在的值', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'UnexpectedValueException' => ['desc' => '不符合预期类型', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'Exception' => ['desc' => '常规错误', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'Throwable' => ['desc' => '通用错误', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'ErrorException' => ['desc' => '用户级别错误', 'level' => '11', 'flag' => 'bad_func', 'type' => 'sys', 'code' => 1],
        'MethodNotAllowedException' => ['desc' => '方法不存在或不可调用', 'level' => '11', 'flag' => 'bad_func', 'type' => 'fm', 'code' => 1],
        'NotFoundException' => ['desc' => '目标找不到', 'level' => '11', 'flag' => 'bad_func', 'type' => 'fm', 'code' => 1],
        'ContainerValueNotFoundException' => ['desc' => '容器内不存在该依赖', 'level' => '11', 'flag' => 'bad_func', 'type' => 'fm', 'code' => 1],
        'SlimContainerException' => ['desc' => '框架容器错误', 'level' => '11', 'flag' => 'bad_func', 'type' => 'fm', 'code' => 1],
    ];
    public function __construct($di)
    {
        if (!empty($di)) {
            self::init($di);
        }
    }

    public static function init($di)
    {
        if (empty(self::$di)) {
            self::$di = $di;
        }
    }

    //被调用后
    public function _invoke($request, $response, $exception)
    {
//        print_r($this->handlerName . PHP_EOL . $exception->getMessage()); exit('exit');
        throw $exception;
//        throw new \Exception('error is :' . $this->handlerName);
//        $body = new Body(fopen('php://temp', 'r+'));
//        if (is_array($exception)) {
//            return $response->withHeader('Content-type', 'application/json')->withBody($body);
//        }
//        $output = $this->handlerName;
//        if ($this->outputBuffering === 'prepend') {
//            // prepend output buffer content
//            $body->write(ob_get_clean() . $output);
//        } elseif ($this->outputBuffering === 'append') {
//            // append output buffer content
//            $body->write($output . ob_get_clean());
//        } else {
//            // outputBuffering is false or some other unknown setting
//            // delete anything in the output buffer.
//            ob_get_clean();
//            $body->write($output);
//        }
//        return $response->withHeader('Content-type', 'application/json')->withBody($body);
//        $this->writeToLog();
//        $this->sendToMail();
//        $this->noticeTo();
//        echo 'exception, 被接管了';
    }

    public static function customErrorHandler($errno, $errstr, $errfile, $errline)
    {
        throw new \ErrorException($errstr, $errno, $errno, $errfile, $errline);
    }

    public static function customShutDownHandler()
    {
        $lastErr = error_get_last();
        if (!empty($lastErr)) {
            throw new \Exception($lastErr['message'], $lastErr['type']);
        }
    }

    public static function customExceptionHandler($exception)
    {
        $gen = self::buildError($exception);
        foreach ($gen as $action) {
            print_r($action);
        }
    }

    protected static function buildError($exception)
    {
//        var_dump($exception->getPrevious()); exit();
        $arr = [
            'error_url' => self::$di['request']->getUri()->getPath(),
            'error_level' => $exception->getCode(),
            'error_message' => $exception->getMessage(),
            'error_file' => $exception->getFile(),
            'error_line' => $exception->getLine(),
            'error_type' => $exception->getCode() ? 'ERROR' : 'EXCEPTION',
            'error_trace' => $exception->getTraceAsString(),
            'error_master' => get_class($exception),
        ];
        yield self::writeToLog($arr);
        yield self::sendToMail($arr);
        yield self::noticeToSms($arr);
        yield self::redirectTo($arr);
        return 0;
    }


    public static function writeToLog($arr)
    {
        print_r($arr);
//        echo 'log' . PHP_EOL;
        //调用写日志，按级别写
    }

    public static function sendToMail($arr)
    {
//        echo 'mail' . PHP_EOL;
        //调用发邮件，按级别发
    }

    public static function noticeToSms($arr)
    {
//        echo 'sms' . PHP_EOL;
        // 调用发短线， 按级别发
    }

    public static function redirectTo($arr)
    {
//        echo 'url' . PHP_EOL;
        // 是否去跳转
    }

    protected function defaultErrorLevelMap()
    {
        return array(
            E_ERROR             => LogLevel::CRITICAL,
            E_WARNING           => LogLevel::WARNING,
            E_PARSE             => LogLevel::ALERT,
            E_NOTICE            => LogLevel::NOTICE,
            E_CORE_ERROR        => LogLevel::CRITICAL,
            E_CORE_WARNING      => LogLevel::WARNING,
            E_COMPILE_ERROR     => LogLevel::ALERT,
            E_COMPILE_WARNING   => LogLevel::WARNING,
            E_USER_ERROR        => LogLevel::ERROR,
            E_USER_WARNING      => LogLevel::WARNING,
            E_USER_NOTICE       => LogLevel::NOTICE,
            E_STRICT            => LogLevel::NOTICE,
            E_RECOVERABLE_ERROR => LogLevel::ERROR,
            E_DEPRECATED        => LogLevel::NOTICE,
            E_USER_DEPRECATED   => LogLevel::NOTICE,
        );
    }

    private static function codeToString($code)
    {
        switch ($code) {
            case E_ERROR:
                return 'E_ERROR';
            case E_WARNING:
                return 'E_WARNING';
            case E_PARSE:
                return 'E_PARSE';
            case E_NOTICE:
                return 'E_NOTICE';
            case E_CORE_ERROR:
                return 'E_CORE_ERROR';
            case E_CORE_WARNING:
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR:
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING:
                return 'E_COMPILE_WARNING';
            case E_USER_ERROR:
                return 'E_USER_ERROR';
            case E_USER_WARNING:
                return 'E_USER_WARNING';
            case E_USER_NOTICE:
                return 'E_USER_NOTICE';
            case E_STRICT:
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR:
                return 'E_RECOVERABLE_ERROR';
            case E_DEPRECATED:
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED:
                return 'E_USER_DEPRECATED';
        }

        return 'Unknown PHP error';
    }
}