<?php
class HelperResponse{

    const SUCCESS = 0;
    const FAIL = 1;
    
    public static function redirect($url, $code = 302)
    {
        header("Location:$url", true, $code);
        exit();
    }

    public static function cookie($name, $value = null, $expire = null, $path = '/', $domain = null, $secure = false, $httpOnly = false)
    {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public static function expires($seconds = 1800)
    {
        $time = date('D, d M Y H:i:s', time() + $seconds) . ' GMT';
        header("Expires: $time");
    }

    public static function resultSuccess($data = null){
        return self::result(self::SUCCESS,"",$data);
    }
    public static function resultFail( $errmsg , $data = null){
        return self::result(self::FAIL, $errmsg, $data);
    }
    public static function result($errno, $errmsg = null, $data = null){
        return array(
            'errno' => $errno,
            'errmsg' => $errmsg,
            'time' => time(),
            'data' => $data,
        );
    }

    public static function jsonOutput($data){
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

}