<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class UserService
{
    public static function createNewUser($pd /** post data*/)
    {
        foreach ($pd as $k => $v)
        {
            $pd[$k] = Html::escape($v);
        }

        $ret = false;
        do
        {
            if ('' == $pd['email'] || '' == $pd['password'] || '' == $pd['repassword'])
            {
                Html::setFlash('电子邮箱和密码不能为空');
                break;
            }

            if (! filter_var($pd['email'], FILTER_VALIDATE_EMAIL))
            {
                Html::setFlash('电子邮箱不合法');
                break;
            }

            if ($pd['password'] != $pd['repassword'])
            {
                Html::setFlash('密码不匹配');
                break;
            }

            /** 检查是否存在 */
            $user_info = UserModel::getUserInfoByEmail($pd['email']);
            if (is_array($user_info) && count($user_info))
            {
                Html::setFlash('用户已经存在');
                break;
            }

            $register_time = time();
            $save_data = array(
                'openid'=> '',
                'email' => $pd['email'],
                'nickname' => isset($pd['nickname']) ? $pd['nickname'] : '',
                'password' => self::createPassword($pd['email'], $pd['password'], $register_time),
                'register_time' => $register_time,

                /** mysql 5.6.23 不容许非 NULL 字段在插入时不给初值 */
                'platform' => 0,
                'mobile' => '',
                'avator' => '',
                'ext'    => '',
            );
            $uid = UserModel::uCreateNewUser($save_data);
            if ($uid <= 0)
            {
                Html::setFlash('创建用户失败，请联系管理员');
                break;
            }

            /** 创建 成功 */
            $_SESSION['user_info'] = array(
                'uid' => $uid,
                'base_info' => UserModel::getUserInfoByUid($uid),
            );
            Html::setFlash('注册成功');

            $ret = true;
        }
        while (0);

        return $ret;
    }

    public static function createPassword($email, $password, $register_time)
    {
        return md5($email . $password . $register_time);
    }

    public static function verifyAccount($email, $password)
    {
        $ret = false;

        do
        {
            $user_info = UserModel::getUserInfoByEmail($email);
            if (! is_array($user_info) || count($user_info) <= 0)
            {
                Html::setFlash('用户不存在');
                break;
            }

            if ($user_info['password'] != self::createPassword($email, $password, $user_info['register_time']))
            {
                Html::setFlash('电子邮箱或密码错误');
                break;
            }

            $_SESSION['user_info'] = array(
                'uid' => $user_info['id'],
                'base_info' => $user_info,
                'flash' => '登陆成功',
            );

            UserModel::updateLastLoginTime(time(), $user_info['id']);

            $ret = true;
        }
        while (0);

        return $ret;
    }

    public static function createSnsUser($type, $userInfo){
        if(empty($type) || empty($userInfo)){
            Html::setFlash('授权失败!');
            SeasLog::error(__METHOD__ . json_encode(func_get_args()));
            header('location: /');
        }

        $platforms = UserModel::getPlatform();
        $platforms = array_flip($platforms);

        $platform = isset($platforms[$type]) ? $platforms[$type] : null;

        if(null == $platform){
            SeasLog::error(__METHOD__ . ' platform:' . $platform);
            header('location: /');
        }

        $register_time = time();
        $save_data = array(
            'openid'=> $userInfo['openid'],
            'unionid' => isset($userInfo['unionid']) ? $userInfo['unionid'] : '',
            'email' => '',
            'nickname' => isset($userInfo['nickname']) ? $userInfo['nickname'] : '',
            'password' => '',
            'register_time' => $register_time,

            /** mysql 5.6.23 不容许非 NULL 字段在插入时不给初值 */
            'platform' => $platform,
            'mobile' => '',
            'avator' => $userInfo['avator'],
            'ext'    => '',
        );
        $uid = UserModel::uCreateNewUser($save_data);
        if ($uid <= 0)
        {
            Html::setFlash('创建用户失败，请联系管理员');
            SeasLog::error(__METHOD__ . ' 创建用户失败:' . json_encode($save_data));
            return false;
        }

        /** 创建 成功 */
        $_SESSION['user_info'] = array(
            'uid' => $uid,
            'base_info' => UserModel::getUserInfoByUid($uid),
        );

        return $save_data;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

