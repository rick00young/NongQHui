<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class rbac_roles_manageAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $rid = $this->getRequestParam('rid', 0);
        if ($rid <= 1)
        {
            Html::setFlash('非法操作');
            $this->redirect('/admin/rbac_roles_view');
            return false;
        }

        $role = RbacService::getRoleByRid($rid);
        if (false === $role)
        {
            Html::setFlash('角色不存在');
            $this->redirect('/admin/rbac_roles_view');
            return false;
        }

        $permissions = RbacService::getAllPermissions();
        //print_r($permissions);
        $permissions_4_role = RbacService::getPermissions4RoleByRid($rid);
        //var_dump($permissions_4_role);

        $tpl = array(
            'rid' => $rid + 0,
            'role' => $role,
            'permissions' => $permissions,
            'permissions_4_role' => $permissions_4_role,
        );
        $this->assign('tpl', $tpl);

        $this->getView()->display('admin/rbac_roles_manage.phtml');
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

