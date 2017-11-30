<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/29
 * Time: 21:28
 */

class CException extends Exception
{
    public static $di = null;
    public static $app = null;

    public static function init($di, $app = null)
    {
        if (empty(self::$di)) {
            self::$di = $di;
        }
        if (empty(self::$app)) {
            self::$app = $app;
        }
    }


    public function __construct($code, ...$placeHolder)
    {

        $codes = self::$di['configHandler']->get('system', 'error_code');
        $message = $codes[$code] ?? "请管理员配置需要替换的错误码表for{$code}";
        //如果 有替换值，并且字符串需要替换，就去替换内部
        if (!empty($placeHolder) && (strpos($message, 's%') !== false)) {
            $message = sprintf($message, $placeHolder);
        } else {
            // 否则占位是空or内容不需要被替换
            $message = strpos($message, 's%') === false ?  $message : "请管理员去配置替换内容的占位符for{$code}";
        }

        return parent::__construct($code, $message);
    }
}