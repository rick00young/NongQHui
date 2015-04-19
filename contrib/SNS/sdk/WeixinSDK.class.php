<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// | TencentSDK.class.php 2013-02-25
// +----------------------------------------------------------------------

class WeixinSDK extends SnsOauth{
	/**
	 * 获取requestCode的api接口
	 * @var string
	 */
	protected $GetRequestCodeURL = 'https://open.weixin.qq.com/connect/qrconnect';
	
	/**
	 * 获取access_token的api接口
	 * @var string
	 */
	protected $GetAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

	/**
	 * API根路径
	 * @var string
	 */
	protected $ApiBase = 'https://api.weixin.qq.com/sns';

    /**
     * scope 应用授权作用域，拥有多个作用域用逗号（,）分隔，网页应用目前仅填写snsapi_login即可
     * @var string
     */
    protected $Scope = 'snsapi_login';


    /**
     * 请求code
     */
    public function getRequestCodeURL(){
        $this->config();
        //Oauth 标准参数
        $params = array(
            'appid'     => $this->AppKey,
            'redirect_uri'  => $this->Callback,
            'response_type' => $this->ResponseType,
            'scope' => $this->Scope,
            'state' => '0000',//csrf
        );


        //获取额外参数
        if($this->Authorize){
            parse_str($this->Authorize, $_param);
            if(is_array($_param)){
                $params = array_merge($params, $_param);
            } else {
                throw new Exception('AUTHORIZE配置不正确！');
            }
        }
        return $this->GetRequestCodeURL . '?' . http_build_query($params);
    }


    /**
     * 获取access_token
     * @param string $code 上一步请求到的code
     */
    public function getAccessToken($code, $extend = null){
        $this->config();
        $params = array(
            'appid'     => $this->AppKey,
            'secret' => $this->AppSecret,
            'grant_type'    => $this->GrantType,
            'grant_type'  => 'authorization_code',
            'code'          => $code,
        );

        $data = $this->http($this->GetAccessTokenURL, $params, 'GET');
        //返回的data数据为json
        $data = json_decode($data, true);
        $data = is_array($data) ? $data : array();

        $this->Token = $this->parseToken($data, $extend);
        return $this->Token;
    }
	
	/**
	 * 组装接口调用参数 并调用接口
	 * @param  string $api    微博API
	 * @param  string $param  调用API的额外参数
	 * @param  string $method HTTP请求方法 默认为GET
	 * @return json
	 */
	public function call($api, $param = '', $method = 'GET', $multi = false){
		/* 腾讯微博调用公共参数 */
		$params = array(
			'access_token'       => $this->Token['access_token'],
			'openid'             => $this->openid(),
		);

		$vars = $this->param($params, $param);
		$data = $this->http($this->url($api), $vars, $method, array(), $multi);
		return json_decode($data, true);
	}
	
	/**
	 * 解析access_token方法请求后的返回值 
	 * @param string $result 获取access_token的方法的返回值
	 */
	protected function parseToken($result, $extend){
		//parse_str($result, $data);
        $data = $result;
        if(is_array($result) && is_array($extend)){
            $data = array_merge($data, $extend);
        }

		if($data['access_token'] && $data['expires_in'] && $data['openid'])
			return $data;
		else
			throw new Exception("获取微信 ACCESS_TOKEN 出错：{$result}");
	}
	
	/**
	 * 获取当前授权应用的openid
	 * @return string
	 */
	public function openid(){
		$data = $this->Token;
		if(isset($data['openid']))
			return $data['openid'];
		else
			throw new Exception('没有获取到openid！');
	}


    public function getUserInfo(){
        if(empty($this->Token)){
            throw new Exception('参数错误');
        }
        $user_info = $this->call('/userinfo');
        return $user_info;
    }
}