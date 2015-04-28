<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class rbac_roles_viewAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $roles = RbacService::getAllRoles();

        $this->assign('roles', $roles);
        $this->getView()->display('admin/rbac_roles_view.phtml');
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

