<?php

class HelperRequest {
    private static $_request = array();

    public static function get($key,$default=''){
        return isset($_GET[$key]) ? addslashes($_GET[$key]) : $default;
    }
    
    public static function post($key,$default=''){
        return isset($_POST[$key]) ? addslashes($_POST[$key]) : $default;
    }

    public static function getParam($key)
    {
        $value = self::get($key);
        if (!empty($value)){
            return self::get($key);
        }
        return self::post($key);
    }

    public static function cookie($key = null, $default = null)
    {
        if (null === $key) {
            return $_COOKIE;
        }

        return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
    }

    public static function header($header)
    {
        if (empty($header)) {
            return null;
        }

        // Try to get it from the $_SERVER array first
        $temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
        if (!empty($_SERVER[$temp])) {
            return $_SERVER[$temp];
        }
        return false;
    }

    public static function isAjax()
    {
        return ('XMLHttpRequest' == self::header('X_REQUESTED_WITH'));
    }
    
    public static function isFlashRequest()
    {
        return ('Shockwave Flash' == self::header('USER_AGENT'));
    }
}
