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
        Yaf_Dispatcher::getInstance()->autoRender(false);
    }

    public $actions = array(
        // key => */key.php action 必须与文件同名,坑

        'index'     => 'admin/index.php',

        'login'     => 'admin/login.php',
        'signin'    => 'admin/signin.php',
        'register'  => 'admin/register.php',
        'logout'    => 'admin/logout.php',
        'create_user'=>'admin/create_user.php',

        /* rbac Permissions, Roles, Users {{{ */
        /** 给不同角色赋不同权限,给用户赋不同角色,来实现权限管理 */
        'rbac_permissions_view'     => 'admin/rbac_permissions_view.php',       /** 查看权限 */
        'rbac_permissions_add'      => 'admin/rbac_permissions_add.php',        /** 增加权限 */
        'rbac_permissions_edit'     => 'admin/rbac_permissions_edit.php',       /** 编辑权限 */
        'rbac_permissions_remove'   => 'admin/rbac_permissions_remove.php',     /** 删除权限 */

        'rbac_roles_view'   => 'admin/rbac_roles_view.php',         /** 查看角色 */
        'rbac_roles_add'    => 'admin/rbac_roles_add.php',          /** 增加角色 */
        'rbac_roles_edit'   => 'admin/rbac_roles_edit.php',         /** 编辑角色 */
        'rbac_roles_remove' => 'admin/rbac_roles_remove.php',       /** 删除角色 */
        'rbac_roles_manage' => 'admin/rbac_roles_manage.php',       /** 角色授权管理界面 */
        'rbac_roles_assign' => 'admin/rbac_roles_assign.php',       /** 给角色授权 */

        'rbac_users_view'   => 'admin/rbac_users_view.php',         /** 查看用户列表 */
        'rbac_users_manage' => 'admin/rbac_users_manage.php',       /** 用户分配角色管理界面 */
        'rbac_users_assign' => 'admin/rbac_users_assign.php',       /** 为用户分配和回收角色 */

        /** }}} */

        'forget'    => '', // 忘记密码

        'upload'    => 'admin/upload.php',

        'newshop'  => 'admin/newshop.php',
        'shoplist'  => 'admin/shoplist.php',
        'shopinfo'  => 'admin/shopinfo.php',
        'editshop'  => 'admin/editshop.php',
        'saveshop'  => 'admin/ajax/shop/saveshop.php',

        'goodlist'  => 'admin/goodlist.php',
        'goodinfo'  => 'admin/goodinfo.php',
        'good_edit' => 'admin/good_edit.php',
        'savegood'       => 'admin/ajax/good/savegood.php',
        'save_good_info' => 'admin/ajax/good/save_good_info.php',
        'good_delete'    => 'admin/ajax/good/good_delete.php',
        'good_online'    => 'admin/ajax/good/good_online.php',
        'good_offline'   => 'admin/ajax/good/good_offline.php',
        'newgood'   => 'admin/newgood.php',
        'goodmoreinfo' => 'admin/goodMoreInfo.php',
        'good_preview' => 'admin/good_preview.php',
    );

}

