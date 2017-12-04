<?php
namespace CallCenter\Exlib;

use Monolog\Logger;

class LoggerMaster
{
    
    private static $loggerCon = [];

    private $logger = null;
    
    private function __construct($logger)
    {
        $this->logger = $logger;
    }

    public static function init($name)
    {
        if (empty(self::$loggerCon[$name])) {
            self::$loggerCon[$name] = new Logger($name);
        }
        return new self(self::$loggerCon[$name]);
    }

    public function set(\Closure $func)
    {
        $func($this->logger);
        return $this->logger;
    }
}