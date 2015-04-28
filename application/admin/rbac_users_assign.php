<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class rbac_users_assignAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $uid = $this->getRequestParam('uid', 0);
        $redirect = '/admin/rbac_users_view';

        do {
            if ($uid <= SUPERVISOR_UID || ! isset($_POST['role_assign']))
            {
                Html::setFlash('提交数据有误');
                break;
            }

            $role_assign = $_POST['role_assign'];
            $all_roles   = RbacService::getAllRoles();
            foreach ($all_roles as $rid => $role_dt)
            {
                $has = RbacService::checkUserHasRoles($rid, $uid);
                /** 回收角色: 原来有,本次管理员要收回 */
                if ($has && ! isset($role_assign[$rid]))
                {
                    RbacService::usersUnassign($rid, $uid);
                }
                /** 已经拥有 */
                elseif ($has && isset($role_assign[$rid]))
                {
                    continue;
                }
                elseif (isset($role_assign[$rid]))
                {
                    RbacService::usersAssign($rid, $uid);
                }

                $redirect = '/admin/rbac_users_manage?uid=' . $uid;
                Html::setFlash('分配角色成功');
            }
        } while (0);

        $this->redirect($redirect);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

