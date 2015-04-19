<?php

//定义回调URL通用的URL
define('URL_CALLBACK', 'http://www.agrovips.com/sns/callback');

define('CALLBACK_SINA', URL_CALLBACK.'?type=sina');
define('CALLBACK_WEIXIN', URL_CALLBACK.'?type=weixin');

class SnsOauthConfig{

    public static $CONFIG = array(

    );

    public static function getConfig($type){

        $type = strtoupper($type);

        return self::$CONFIG["SNS_LOGIN_${type}"];
    }
}