<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */

/* vi:set ts=4 sw=4 et fdm=marker: */
class WxController extends Yaf_Controller_Abstract
{
    // 所有 action 执行前会调用
    public function init()
    {
        // ADD CODE
    }

    public function debugAction()
    {
        //print_r($_SERVER);
        //echo Yaf_Registry::get('config')->cookie->domain;

        echo Html::wxJsSDK();

        return false;
    }

    public function indexAction()
    {
        $oauth2_url = WxAPI::createOauth2Url();
        if (!isset($_COOKIE['_wx_userinfo_']))
        {
            header('Location: ' . $oauth2_url);
        }
        else
        {
            echo 'OK, welcome.';
            //header('Location: /h5/201501/xlpp/index.html');
        }

        return false;
    }

    // 生成引导用户授权 url
    public function getOauth2UrlAction()
    {
        $state = isset($_GET['state']) && strlen($_GET['state']) ? $_GET['state'] : '1331';
        $oauth2_url = WxAPI::createOauth2Url($state);

        echo $oauth2_url;
        return false;
    }

    // 授权取用户基本信息,以 base64 存入 cookie
    public function oauth2Action()
    {
        $userinfo_json = WxAPI::getWxUserinfo($_GET);

        if (strlen($userinfo_json))
        {
            // 有效期一年
            setcookie('_wx_userinfo_', base64_encode($userinfo_json), time() + 31536000, '/', Yaf_Registry::get('config')->cookie->domain);
        }

        switch ($_GET['state'])
        {
        case 1314:
            // do something
            break;

        default:
            echo 'OK';
        }
        return false;
    }

    public function wxConfigAction()
    {
        header('Content-Type:application/x-javascript');

        $debug = 0;
        if (isset($_REQUEST['debug']) && $_REQUEST['debug'])
        {
            $debug = 1;
        }

        if (! isset($_REQUEST['url']))
        {
            $res = array('error' => 110, 'msg' => 'lost param');
            echo 'window.api_req = ' . json_encode($res) . ';';

            return false;
        }

        $access_token = WxAPI::getAccessToken();
        //echo 'access_token: ' . $access_token . PHP_EOL;

        $url = urldecode($_REQUEST['url']);
        $noncestr = WxAPI::createNoncestr(16);
        $timestamp= time();
        $jsapi_ticket = WxAPI::getJsapiTicket($access_token);
        //echo 'jsapi_ticket: ' . $jsapi_ticket . PHP_EOL;
        //exit;

        $sign_data = array(
            'noncestr'      => $noncestr,
            'jsapi_ticket'  => $jsapi_ticket,
            'timestamp'     => $timestamp,
            'url' => $url,
        );
        $signature = WxAPI::createSignature($sign_data);
        $appid = WxAPI::appid;

        $output = <<<JS
var _wx_config = {
  debug: {$debug},
  appId: '{$appid}',
  timestamp: {$timestamp},
  nonceStr: '{$noncestr}',
  signature: '{$signature}',
  jsApiList: [
    'checkJsApi',
    'onMenuShareTimeline',
    'onMenuShareAppMessage',
    'onMenuShareQQ',
    'onMenuShareWeibo',
    'hideMenuItems',
    'showMenuItems',
    'hideAllNonBaseMenuItem',
    'showAllNonBaseMenuItem',
    'translateVoice',
    'startRecord',
    'stopRecord',
    'onRecordEnd',
    'playVoice',
    'pauseVoice',
    'stopVoice',
    'uploadVoice',
    'downloadVoice',
    'chooseImage',
    'previewImage',
    'uploadImage',
    'downloadImage',
    'getNetworkType',
    'openLocation',
    'getLocation',
    'hideOptionMenu',
    'showOptionMenu',
    'closeWindow',
    'scanQRCode',
    'chooseWXPay',
    'openProductSpecificView',
    'addCard',
    'chooseCard',
    'openCard'
  ]
};
wx.config(_wx_config);
JS;

        echo $output;

        return false;
    }
}
