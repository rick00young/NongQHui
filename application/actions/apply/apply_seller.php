<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class apply_sellerAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        if(true !== $this->_islogin){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'please login first', new stdClass());
            $this->jsonReturn($returnData);
        }
        $name = trim($this->post('apply_name'));
        $phone = trim($this->post('apply_phone'));
        $email = trim($this->post('apply_email'));
        $address = trim($this->post('apply_address'));
        $uid = $this->getUid();
        $userInfo = $this->getUserInfo();
        $nick = isset($userInfo['nickname']) ? $userInfo['nickname'] : '';

        if(!$name || !$phone || !$address){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'param error', '');
              $this->jsonReturn($returnData);
        }

        $redis = RedisCache::getInstance();
        $cacheKey = 'apply_seller_' . $uid;

        /*
        if($redis->get($cacheKey)){
            $returnData = HelperResponse::result(3, '您已经申请一次了,请12小时后再试!', '');
            $this->jsonReturn($returnData);
        }
        */

        $title =  $name .'-申请开店-' .date('Y-m-d H:i:s');
        //$title = "=?UTF-8?B?".base64_encode($title)."?=";
        $body = '
				<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>无标题文档</title>
					<style>
						body { font-family:"Microsoft Yahei"; font-size:16px;}
						p {  margin-top: 10px; padding-left: 10px;}
						p span { font-weight:bold; font-size:24px; background:#000; color:#fff; padding:5px 10px;}
						p b { color:#3eaf0e;}
						h2 { padding-left: 10px; font-size:18px;}
					</style>
					</head>

					<body>
					<h2>'.$title.'</h2>
					<p>客户姓名: '.$name.'</p>
					<p>客户昵称: '.$nick.'</p>
					<p>客户ID: '.$uid.'</p>
					<p>联系电话: '.$phone.'</p>
					<p>电子邮箱: '.$email.'</p>
					<p>联系地址: '.$address.'</p>
					<p>申请日期: '.date('Y-m-d H:i:s').'</p>
					<p>(这是一封自动产生的email，请勿回复)</p>
				</body>
				</html>
				';

        $result = Mailer::sendMailFromService('yyr168yyr@163.com', $title, $body, array());
        if($result){

            //一天只能申请一次
            $redis->set($cacheKey, 1, 43200);//12小时后过期 43200
            $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success', '');
        }else{
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'fail', '');
        }

        $this->jsonReturn($returnData);
    }
}
