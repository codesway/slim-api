<?php

namespace CC\Codebase\Config;
class ConfigHandler
{
    private static $env = null;
    private static $conf = [];
    private static $set = [];
    private static $args = [];

    private static $instance = null;
    private function __construct($env, $args = [])
    {
        self::$env = $env['mode'];
        self::$args = func_get_args();
    }

    public static function init()
    {
        if (empty(self::$instance)) {
            self::$instance = new self(self::getEnv(), func_get_args());
        }
        return self::$instance;
        //把自己加入到DI
    }

    private static function getEnv()
    {
        $filename = CODEBASE_ROOT . 'config' . DS . '.env';
        if (!file_exists($filename)) {
            throw new Exception('sys.codebase.env: not exist');
        }

        return include $filename;
    }

    public static function get($name, $key = null)
    {
        if (!self::$env) {
            throw new \Exception('sys.codebase.env:load error');
        }

        if (empty(self::$conf[$name])) {
            self::load($name);
        }

        return empty($key) ? self::$conf[$name] : self::$conf[$name][$key] ?? null;
    }

    private static function load($name)
    {
        $filename = CODEBASE_ROOT . 'config' . DS . self::$env . DS . $name . '.php';
        if (!file_exists($filename)) {
            throw new \Exception('sys.codebase.env:conf not exist');
        }
        self::$conf[$name] = include $filename;
    }

    public static function set($name, $value)
    {
        if (!empty(self::$set[$name])) {
            throw new Exception('sys.codebase.env:conf is exist');
        }

        self::$set[$name] = $value;
    }
}