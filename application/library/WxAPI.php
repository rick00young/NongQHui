<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class WxAPI
{
    // 授权后的跳转地址,取用户信息的逻辑在其中完成
    /** 需要根据需求换成线上真实可访问的外网地址 */
    private static $redirect_uri = 'http://%s/wx/oauth2';

    const cache_prefix = '__wxapi_';

    // 服务号的 appid
    const appid  = '';
    // 服务号的私钥
    const secret = '';

    // 微信官方要求缓存 token, 生存期 7200秒
    public static function getAccessToken()
    {
        $c_key = 'access_token';
        $access_token = self::cacheGet($c_key);
        if (false !== $access_token)
        {
            return $access_token;
        }
        else
        {

            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
            $api = sprintf($url, self::appid, self::secret);

            $token_json = self::get($api);
            $token_dt   = json_decode($token_json, true);

            if (! is_array($token_dt) || ! isset($token_dt['access_token']))
            {
                echo 'get access_token failure.';
                exit();
            }

            $access_token = $token_dt['access_token'];
            self::cacheSet($c_key, $access_token, 7140);
        }

        return $access_token;
    }

    // 微信官方要求缓存 jsapi_ticket, 生存期 7200秒
    public static function getJsapiTicket($access_token)
    {
        $c_key = 'jsapi_ticket';
        $jsapi_ticket = self::cacheGet($c_key);

        if (false !== $jsapi_ticket)
        {
            return $jsapi_ticket;
        }
        else
        {
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi';
            $api = sprintf($url, $access_token);

            $ticket_json = WxAPI::get($api);
            $ticket_data = json_decode($ticket_json, true);

            if (! is_array($ticket_data) || 0 != $ticket_data['errcode'])
            {
                echo 'get jsapi_ticket failure.';
                exit;
            }

            $jsapi_ticket = $ticket_data['ticket'];
            self::cacheSet($c_key, $jsapi_ticket, 7140);
        }

        return $jsapi_ticket;
    }

    public static function createSignature($sign_data)
    {
        ksort($sign_data);

        $dt = array();
        foreach ($sign_data as $k => $v)
        {
            $dt[] = "{$k}={$v}";
        }

        $signature = sha1(implode('&', $dt));
        return $signature;
    }

    public static function createOauth2Url($state = 1314)
    {
        // 授权取详细信息
        $redirect_uri = sprintf(self::$redirect_uri, $_SERVER['SERVER_NAME']);
        $auth = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=%s#wechat_redirect';
        $auth_url = sprintf($auth, self::appid, urlencode($redirect_uri), $state);

        return $auth_url;
    }

    /**
     * 如果相取用户 subscribe 状态,参看 @see: http://mp.weixin.qq.com/wiki/14/bb5031008f1494a59c6f71fa0f319c66.html
     * */

    /**
     * @brief getWxUserinfo
     * @see http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
     *
     * @param: $req array 微信发送过来请求
     * @param: $decode bool 0: 返回 json 串; 1: 返回 php 数组
     *
     * @return: mixed
     */
    public static function getWxUserinfo($req, $decode = 0)
    {
        if (! isset($req['code']))
        {
            exit('bad request.');
        }

        /** 此模块属于低频次被微信服务器调用,没有缓存价值与必要 */
        $code = $req['code'];

        // 取 token
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
        $get_access_token_url = sprintf($url, self::appid, self::secret, $code);

        $token_json = self::get($get_access_token_url);
        //echo $token_json;

        $token_dt = json_decode($token_json, true);
        if (! is_array($token_dt) || ! isset($token_dt['access_token']))
        {
            exit('get token failure...');
        }

        // 取用户信息
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=zh_CN';
        $get_userinfo_url = sprintf($url, $token_dt['access_token'], $token_dt['openid']);
        $userinfo_json = self::get($get_userinfo_url);

        if ($decode)
        {
            return json_decode($userinfo_json, true);
        }
        else
        {
            return $userinfo_json;
        }
    }

    public static function get($url, $timeout = 30)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 以流式返回,而不是直接输出

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout - 10);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    public static function createNoncestr($length, $numeric = 0)
    {
        if($numeric)
        {
            /*格式化字串,共有 $length 位,少于则有 0 补*/
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
            //number pow ( number base, number exp );
             //返回 base 的 exp 次方的幂;
        } else
         {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            $max = strlen($chars) - 1;
             for($i = 0; $i < $length; $i++)
             {
                $hash .= $chars[mt_rand(0, $max)];
             }
         }

         return $hash;
    }

    // cache, 简单的 redis 封装
    // 有改进空间
    private static function getCacheHandle()
    {
        $redis = new Redis();
        $conf  = Yaf_Registry::get('config')->cache->key_value->toArray();
        $redis->connect($conf['hostname'], $conf['port']);

        return $redis;
    }

    public static function cacheSet($key, $value, $expire = 3600)
    {
        $key = self::cache_prefix . $key;
        $c = self::getCacheHandle();
        $c->set($key, $value);
        $c->setTimeout($key, $expire);
    }

    public static function cacheGet($key)
    {
        $key = self::cache_prefix . $key;
        $c = self::getCacheHandle();
        return $c->get($key);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

