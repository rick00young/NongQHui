<?php
/**
 * @describe:
 * @author: Rick Yang
 * */

/* vi:set ts=4 sw=4 et fdm=marker: */

class SnsController extends Yaf_Controller_Abstract {

	public function indexAction($name = "Stranger") {

	}

    public function loginAction(){
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        $type = $_GET['type'];

        if(empty($type)){
            $this->redirect('/');
        }

        //TODO是否已经登录
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
        $sns  = SnsOauth::getInstance($type);


        //腾讯微博需传递的额外参数
        $extend = null;
        if($type == 'tencent'){
            $extend = array('openid' => $this->_get('openid'), 'openkey' => $this->_get('openkey'));
        }

        $token = $sns->getAccessToken($code , $extend);
        //token需要缓存
        $userInfo = $sns->getUserInfo();
    }

}
