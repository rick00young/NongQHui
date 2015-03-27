<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class rbac_users_manageAction extends BaseAction
{
    public function run($arg = null)
    {
        //echo __METHOD__;
        $uid = $this->getRequestParam('uid', 0) + 0;
        if ($uid <= SUPERVISOR_UID)
        {
            Html::setFlash('参数非法');
            $this->redirect('/admin/rbac_users_view');
            return false;
        }

        $all_roles  = RbacService::getAllRoles();
        $user_roles = RbacService::getRoles4UserByUid($uid);
        $user_info  = UserModel::getUserInfoByUid($uid);

        $tpl = array(
            'all_roles'  => $all_roles,
            'user_roles' => $user_roles,
            'user_info'  => $user_info,
        );
        //print_r($tpl);
        $this->assign('tpl', $tpl);
        $this->getView()->display('admin/rbac_users_manage.phtml');
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

