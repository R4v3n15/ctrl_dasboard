<?php

class Config
{
    // this is public to allow better Unit Testing
    public static $config;
    public static $params;

    public static function get($key)
    {
        if (!self::$config) {

            $config_file = '../app/config/config.' . Environment::get() . '.php';

            if (!file_exists($config_file)) {
                return false;
            }

            self::$config = require $config_file;
        }

        return self::$config[$key];
    }

    // Construct the DB params
    public static function db($key){
        if (!self::$params) {
            $params_file = '../app/config/dbconfig.php';
            if (!file_exists($params_file)) {
                return false;
            }
            self::$params = require $params_file;
        }

        return self::$params[$key];
    }
}
