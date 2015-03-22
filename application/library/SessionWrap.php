<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class SessionWrap
{
    public static function checkSession()
    {
        return isset($_SESSION) && isset($_SESSION['user_info']) 
            && isset($_SESSION['user_info']['base_info'])
            && count($_SESSION['user_info']['base_info']);
    }

    public static function printNickname()
    {
        if (self::checkSession())
        {
            echo $_SESSION['user_info']['base_info']['nickname'];
        }
    }

    public static function printLastLoginTime()
    {
        if (self::checkSession() && $_SESSION['user_info']['base_info']['last_login_time'] > 0)
        {
            echo '最后登陆时间：' . date('Y-m-d H:i', $_SESSION['user_info']['base_info']['last_login_time']);
        }
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

