<?php
/**
 * @describe:
 * @author: Rick Yang
 * */

/* vi:set ts=4 sw=4 et fdm=marker: */

class SnsController extends Yaf_Controller_Abstract {

    const REFERER_KEY = 'login_referer';
    const SNS_TOKEN_KEY = 'sns_token';

	public function indexAction($name = "Stranger") {}

    public function loginAction(){
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        $type = $_GET['type'];

        if(empty($type)){
            $this->redirect('/');
        }

        $_SESSION[self::REFERER_KEY] = $this->getReferer();

        //TODO 是否已经登录

        Yaf_loader::import(APPLICATION_PATH . '/contrib/SNS/SnsOauth.php');
        $sns = SnsOauth::getInstance($type);
        $this->redirect($sns->getRequestCodeURL());
    }

    public function callbackAction(){
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        $type = $_GET['type'];
        $code = $_GET['code'];

        if(empty($type) || empty($code)){
            $this->redirect('/');
        };

        Yaf_loader::import(APPLICATION_PATH . '/contrib/SNS/SnsOauth.php');
        //请妥善保管这里获取到的Token信息，方便以后API调用
        //调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
        //如： $qq = SnsOauth::getInstance('qq', $token);

        $redis = RedisCache::getInstance();
        $cacheKey = self::SNS_TOKEN_KEY . '_' . $type;
        $cache = $redis->get($cacheKey);
        $token = json_decode($cache, true);

        if(empty($token)){
            $sns  = SnsOauth::getInstance($type);
            //腾讯微博需传递的额外参数
            $extend = null;
            if($type == 'tencent'){
                //$extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
            }
            $token = $sns->getAccessToken($code , $extend);

            //token需要缓存
            $redis->set($cacheKey,json_encode($token), $token['expires_in'] - 100);
        }

        $sns  = SnsOauth::getInstance($type, $token);

        $userInfo = $sns->getUserInfo();


        $referer = $_SESSION[self::REFERER_KEY];
        $referer = $referer ? $referer : '/';

        $userRes = UserModel::getUserInfoByOpenId($userInfo['openid']);
        if(is_array($userRes)){

            $_SESSION['user_info'] = array(
                'uid' => $userRes['id'],
                'base_info' => $userRes,
            );

            //TODO 跳到referer页面
            $this->redirect($referer);

            return false;
        }

        $saveRes = UserService::createSnsUser(strtolower($type), UserModel::getUserInfoFromSnsData($type, $userInfo));
        if(!$saveRes){
            echo '授权失败!';
        }else{
            //TODO 跳到referer页面
            $this->redirect($referer);
            echo '授权成功!';
            return false;
        }

    }

    protected function getReferer(){
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }

}
