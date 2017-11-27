<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/27
 * Time: 10:37
 */
namespace CC\Core\Base;

class EHandler
{
    public static $di = null;
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
        throw new \Exception($errstr, $errno);
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
        $arr = [
            'error_url' => self::$di['request']->getUri()->getPath(),
            'error_level' => $exception->getCode(),
            'error_message' => $exception->getMessage(),
            'error_file' => $exception->getFile(),
            'error_line' => $exception->getLine(),
            'error_type' => $exception->getCode() ? 'ERROR' : 'EXCEPTION',
            'error_trace' => $exception->getTraceAsString(),
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
}