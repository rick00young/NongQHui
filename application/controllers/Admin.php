<?php
/**
 * @describe:
 * @author: Rick Yang
 * */

/* vi:set ts=4 sw=4 et fdm=marker: */

class AdminController extends Yaf_Controller_Abstract
{
    public function init()
    {
        // 后台管理通过服务器站的 session 实现
        // 不支持管理员记住登陆状态
        // session.gc_maxlifetime 默认 1440
        session_start();
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    public $actions = array(
        'index'     => 'admin/index.php',

        'login'     => 'admin/login.php',
        'signin'    => 'admin/signin.php',
        'register'  => 'admin/register.php',
        'logout'    => 'admin/logout.php',
        'create_user'=>'admin/create_user.php',

        'forget'    => '', // 忘记密码

        'upload'    => 'admin/upload.php',

        'newshop'  => 'admin/newshop.php',
        'shoplist'  => 'admin/shoplist.php',
        'shopinfo'  => 'admin/shopinfo.php',
        'editshop'  => 'admin/editshop.php',
        'saveshop'  => 'admin/ajax/shop/saveshop.php',

        'goodlist'  => 'admin/goodlist.php',
        'goodinfo'  => 'admin/goodinfo.php',
        'goodsave'  => 'admin/goodsave.php',
        'newgood'   => 'admin/newgood.php',
        'goodmoreinfo' => 'admin/goodMoreInfo.php',
    );

}

