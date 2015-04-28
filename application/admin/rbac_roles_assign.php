<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class rbac_roles_assignAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $rid = $this->getRequestParam('rid', 0);
        $redirect = '/admin/rbac_roles_view';

        do {
            if ($rid <= 1 || ! isset($_POST['ps']))
            {
                Html::setFlash('提交数据有误');
                break;
            }

            $ps = $_POST['ps'];
            $permissions = RbacService::getAllPermissions();
            foreach ($permissions as $pid => $prmss)
            {
                $has = RbacService::checkRoleHasPermission($rid, $pid);
                /** 回收权限: 原来有,本次收回 */
                if ($has && ! isset($ps[$pid]))
                {
                    RbacService::unassign($rid, $pid);
                }
                /** 已经存在 */
                elseif ($has && isset($ps[$pid]))
                {
                    continue;
                }
                elseif (isset($ps[$pid]))
                {
                    RbacService::assign($rid, $pid);
                }
            }

            $redirect = '/admin/rbac_roles_manage?rid=' . $rid;
            Html::setFlash('授权成功');
        } while (0);

        $this->redirect($redirect);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

