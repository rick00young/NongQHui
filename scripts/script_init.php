<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/5/8
 * Time: 下午3:53
 */

if('test' == ini_get('yaf.environ')){
    error_reporting(E_ALL);
}

define('APPLICATION_PATH', realpath(dirname(__FILE__)) . '/..');
$application = new Yaf_Application( APPLICATION_PATH . "/conf/application.ini");
$application->bootstrap();



