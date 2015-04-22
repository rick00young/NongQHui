<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class Widget
{
    const good_pv_ckey = 'Widget_good_pv_ckey_';

    // 暂时不造假,增加开发成本
    public static function getPV($gid)
    {
        $rdb = RedisDB::getInstance();
        return $rdb->get(self::good_pv_ckey . $gid) + 0;
    }

    public static function increasePV($gid)
    {
        $rdb = RedisDB::getInstance();
        $rdb->incr(self::good_pv_ckey . $gid);
    }

    public static function isWeixinBrowser()
    {
        $ret = false;
        $ua  = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        if ('' == $ua)
        {
            return $ret;
        }

        $p_ret = preg_match('/MicroMessenger/i', $ua, $matches);
        if ($p_ret)
        {
            $ret = true;
        }

        return $ret;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

