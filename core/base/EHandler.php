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
    public static $app = null;

    public static $exceptionMap = [
        'Exception' => [
            'desc' => '通用异常', 'type' => 'sys', 'status' => 10001, 'flag' => 'common_exception'
        ],
        'CException' => [
            'desc' => '业务自定义错误', 'type' => 'option', 'status' => 10002, 'flag' => 'common_err'
        ],
        'Throwable' => [
            'desc' => '通用错误', 'type' => 'sys', 'status' => 10003, 'flag' => 'common_err'
        ],
        'ErrorException' => [
            'desc' => '用户级错误', 'type' => 'sys', 'status' => 10004, 'flag' => 'user_err'
        ],
        'MethodNotAllowedException' => [
            'desc' => '方法不存在或不可调用错误', 'type' => 'slim', 'status' => 10005, 'flag' => 'notfound_err'
        ],
        'NotFoundException' => [
            'desc' => '找不到目标', 'level', 'type' => 'slim', 'status' => 10006, 'flag' => 'notfound_err'
        ],
        'ContainerValueNotFoundException' => [
            'desc' => '容器内不存在该依赖错误', 'type' => 'slim', 'status' => 10007, 'slim_err'
        ],
        'SlimContainerException' => [
            'desc' => '框架容器错误', 'type' => 'slim', 'status' => 10008, 'slim_err'
        ],
    ];

    public $e = null;

    public function __construct($di)
    {
        if (empty(self::$di)) {
            self::init($di);
        }
    }

    public static function init($di, $app = null)
    {
        if (empty(self::$di)) {
            self::$di = $di;
        }
        if (empty(self::$app)) {
            self::$app = $app;
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
        //最终都在这里被调用
//        $gen = self::execute(self::buildError($exception));
//        foreach ($gen as $func) {
//            $func;
//        }
        $exceptionInfo = self::buildError($exception);
//        print_r($exceptionInfo); exit();
        return self::renderToResponse($exceptionInfo);
    }

    protected static function buildError($exception)
    {
        $exceptionType = explode('\\', get_class($exception));
        $handlerMaster = array_pop($exceptionType);
        $map = self::$exceptionMap[$handlerMaster] ?? self::$exceptionMap['Exception'];
        $code = $exception->getCode();
        if (empty($code) || $code >= 100000) {
            $level = empty($code) ? E_ERROR : $code;  //1,致命错误
            $exceptionRet = explode('|', $exception->getMessage());
            $message = !empty($exception->getMessage()) ? $exceptionRet[0] : $map['desc'];
        } else {
            $level = $code; //错误级别
            $message = !empty($exception->getMessage()) ? $exception->getMessage() : $map['desc'];
        }
        if ($code > 100000) {
            list($module, $flag) = explode(':', $exceptionRet[1]);
        } else {
            //非用户自定义的
            $module = $map['type'];
            $flag = $map['flag'];
        }
        return [
            'error_url' => self::$di['request']->getUri()->getPath(),
            'error_level' => $level,
            'error_message' => $message,
            'error_master' => $handlerMaster,
            'error_file' => $exception->getFile(),
            'error_line' => $exception->getLine(),
            'error_status' => $map['status'],
            'error_type' => $map['type'],
            'error_module' => $module,
            'error_flag' => $flag,
            'error_trace' => $exception->getTraceAsString(),
        ];
    }


    protected static function execute($message)
    {
        yield self::writeToLog($message);
        yield self::sendToMail($message);
        yield self::noticeToSms($message);
        yield self::redirectTo($message);
        return 0;
    }


    public static function renderToResponse($arr)
    {
        $errResponse = [
            'status' => $arr['error_level'],
            'data' => [
                'code' => $arr['error_flag'],
                'desc' => $arr['error_message'],
            ],
        ];
        echo json_encode($errResponse, JSON_UNESCAPED_UNICODE); exit();
//        $response = self::$di->get('response');
//        $obj = $response->withJson($errResponse);
//        echo $obj; exit();
//        return self::respond($obj);
    }

    private static function isEmetyResponse($response)
    {
        if (method_exists($response, 'isEmpty')) {
            return $response->isEmpty();
        }
        return in_array($response->getStatusCode(), [204, 205, 304]);
    }

    public static function respond($response)
    {

        // Send response
        if (!headers_sent()) {
            // Headers
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }

            // Set the status _after_ the headers, because of PHP's "helpful" behavior with location headers.
            // See https://github.com/slimphp/Slim/issues/1730

            // Status
            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
        }

        // Body
        if (!self::isEmptyResponse($response)) {
            $body = $response->getBody();
            if ($body->isSeekable()) {
                $body->rewind();
            }
            $settings       = self::$di->get('settings');
            $chunkSize      = $settings['responseChunkSize'];

            $contentLength  = $response->getHeaderLine('Content-Length');
            if (!$contentLength) {
                $contentLength = $body->getSize();
            }


            if (isset($contentLength)) {
                $amountToRead = $contentLength;
                while ($amountToRead > 0 && !$body->eof()) {
                    $data = $body->read(min($chunkSize, $amountToRead));
                    echo $data;

                    $amountToRead -= strlen($data);

                    if (connection_status() != CONNECTION_NORMAL) {
                        break;
                    }
                }
            } else {
                while (!$body->eof()) {
                    echo $body->read($chunkSize);
                    if (connection_status() != CONNECTION_NORMAL) {
                        break;
                    }
                }
            }
        }
    }

    public static function writeToLog($arr)
    {
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