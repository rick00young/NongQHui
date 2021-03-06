<?php
class LibCookie {

    public static function setCookie($key, $value = null, $expire = null, $path = null,
        $domain = null, $secure = null, $httponly = null) {
        setcookie($key, $value, $expire, $path, $domain, $secure, $httponly);
        $_COOKIE[$key] = $value;
    }

    public static function getCookie($key) {
        if (isset($_COOKIE[$key])) {
        	return $_COOKIE[$key];
        }
        return null;
    }

    public static function removeCookie($key) {
        self::setCookie($key, null, 0, '/');
    }

    public static function getFirstCookieByPre($pre) {
    	foreach($_COOKIE as $name=>$value) {
    		if(strpos($name, $pre) === 0){
    			return $value;
    		}
    	}
    	return null;
    }
}