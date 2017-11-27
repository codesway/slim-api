<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/27
 * Time: 10:37
 */
namespace CC\Core\Base;


abstract class ExceptionBase
{
    public $mode = null;
    public $data = [];
    public $di = null;
    public $message = 'trigger_error';
    public $code = 499;
    public function __construct()
    {
        parent::__construct(empty($message) ? $this->message : $message, empty($code) ? $this->code : $code);
    }

    protected function writeToLog()
    {

    }

    protected function sendToMail()
    {

    }

    protected function noticeTo()
    {

    }

    protected function redirectTo()
    {

    }

    //被调用后
    public function __invoke()
    {
        $this->writeToLog();
        $this->sendToMail();
        $this->noticeTo();
        echo 'exception, 被接管了';
        // TODO: Implement __invoke() method.
    }
}