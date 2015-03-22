<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午1:21
 */

class HelperConfig
{
    const DEV_ENV    = 'dev';
    const TEST_ENV   = 'test';
    const ONLINE_ENV = 'online';

    private static $_runMode;

    private static $_mapping;

    public static function init()
    {
        self::_setRunMode(self::getRunMode());
        self::_setFileMapping();
        //include_once(ConfigEnv::getInstance()->getConfigPath().'/constant.php');
    }

    public static function get($key)
    {
        $configPath = APPLICATION_PATH.'/conf/'.trim(self::$_mapping[$key],'/');

        if (! file_exists($configPath)) {
            throw new ExceptionRequest('config ' . self::$_mapping[$key] . 'does not exists');
        }

        $config = include($configPath);
        return $config[self::$_runMode];
    }

    public static function getRunMode()
    {
        if ($_SERVER['ENVIRONMENT'] == self::ONLINE_ENV) {
            return self::ONLINE_ENV;
        }
        if ($_SERVER['ENVIRONMENT'] == self::TEST_ENV) {
            return self::TEST_ENV;
        }
        return self::DEV_ENV;
    }


    private static function _setFileMapping()
    {
        $configPath = APPLICATION_PATH .'/conf/config.php';

        self::$_mapping= include $configPath;
    }

    private static function _setRunMode($mode)
    {
        self::$_runMode = $mode;
    }
}
