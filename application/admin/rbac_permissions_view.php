<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class rbac_permissions_viewAction extends BaseAction
{
    public function run($arg = null)
    {
        $rbac = RbacService::getInstance();

        $permissions = RbacService::getAllPermissions();

        $this->assign('permissions', $permissions);
        $this->getView()->display('admin/rbac_permissions_view.phtml');
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

