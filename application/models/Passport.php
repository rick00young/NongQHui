<?php
/**
 * @name PassportModel
 * @desc Passport处理用户登录 数据加密 cookie session等
 * @author rick
 */
class PassportModel {
    public function __construct() {

    }   

    public static function getTokenFromCookie()
    {
        $encodeToken = LibCookie::getCookie(self::cookieKeyForToken());
        $strToken = LibEncipher::decryption($encodeToken);
        $result = unserialize(base64_decode($strToken));
        return $result;
    }


    /**
     * 完成认证，保存token到cookie中
     * @param array $token
     */
    public static function saveToken2Cookie($loginType, $token)
    {
        if(0 === $token['expires_in'])
            $expire = 0;
        else
            $expire = time() + $token['expires_in'];

        
        $domain = Yaf_Registry::get('config')->cookie->domain;;
        $strToken = base64_encode(serialize($token));
        $encodeToken = LibEncipher::encryption($strToken);

        LibCookie::setCookie(C_LOGIN_TYPE_KEY, $loginType, $expire, '/', $domain);
        LibCookie::setCookie(self::cookieKeyForToken(), $encodeToken, $expire, '/', $domain);
    }

    /**
     * 返回token在cookie中的key
     * @return string
     */
    public static function cookieKeyForToken() {
        return PERMIT_COOKIE;
    }

    /**
     * 删除cookie的token
     * @return string
     */
    public static function removeTokenOfCookie() {
        $cookieKey = self::cookieKeyForToken();
        $domainRoot = Yaf_Registry::get('config')->cookie->domain;;
        LibCookie::setCookie($cookieKey, null, 0, '/', $domainRoot);
    }
    /**
     * 生成数字和字母的验证码
     * @param int 位数
     * @return MResult
     */
    public static function createPin($length)
    {
        $authnum = '';
        //生产验证码字符
        $ychar="2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,U,V,W,X,Y,Z";
        $list = explode(",", $ychar);
        $ylen = count($list);
        for($i = 0; $i < $length; $i++){
            $randnum = rand(0, $ylen - 1);
            $authnum .= $list[$randnum];
        }
        return $authnum;
    }

    /**
     * create verify img
     * @param int $uid
     * @param str $passwd
     * @return MResult
     */
    public static function createPinImg($imgHeight, $imgWidth, $authnum)
    {
        $aimg = imagecreate($imgHeight, $imgWidth);    //生成图片
        imagecolorallocate($aimg, 255, 255, 255);        //图片底色，ImageColorAllocate第1次定义颜色PHP就认为是底色了
        $black = imagecolorallocate($aimg, 0, 0, 0);     //定义需要的黑色

        for ($i = 1; $i <= 100; $i++)
            imagestring($aimg, 1, mt_rand(1, $imgHeight), mt_rand(1, $imgWidth), "#", imagecolorallocate($aimg, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255)));

        //为了区别于背景，这里的颜色不超过200，上面的不小于200
        for ($i=0; $i < strlen($authnum); $i++)
            imagestring($aimg, 5, $i * $imgHeight / strlen($authnum) + mt_rand(2, 7), mt_rand(1, $imgWidth / 2 - 2), $authnum[$i], imagecolorallocate($aimg, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200)));
            // imagestring($aimg, mt_rand(3, 5), $i * $imgHeight / strlen($authnum) + mt_rand(2, 7), mt_rand(1, $imgWidth / 2 - 2), $authnum[$i], imagecolorallocate($aimg, mt_rand(0, 100), mt_rand(0, 150), mt_rand(0, 200)));

        imagerectangle($aimg, 0, 0, $imgHeight - 1, $imgWidth - 1, $black);
        //画一个矩形
        Header("Content-type: image/PNG");
        ImagePNG($aimg);                    //生成png格式
        ImageDestroy($aimg);
    }

    /**
     * 检查图片验证码或字符验证码
     * @param str $type 验证码类型
     * @param str $pin 用户输入的的验证码
     * @return bool
     * add by yangyr
     */
    public static function checkPin($type, $pin) {
        if(empty($pin))
            return false;

        $key = PIN;
        $sessPinEncode = $_SESSION[$key];
        $sessPin = LibEncipher::decryption($sessPinEncode);
        if(empty($sessPin) || strlen($sessPin) !== strlen($pin))
            return false;

        for($i = 0; $i < strlen($sessPin); $i++){
            if(strtolower($pin[$i]) !== $sessPin[$i] && strtoupper($pin[$i]) !== $sessPin[$i])
                return false;
        }
        return true;
    }
}
