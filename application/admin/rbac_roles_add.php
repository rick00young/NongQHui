<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class rbac_roles_addAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $title       = '';
        $description = '';
        $redirect    = '/admin/rbac_roles_view';

        do {
            if (! isset($_REQUEST['title']) || strlen($_REQUEST['title']) <= 0
                || ! isset($_REQUEST['description']) || strlen($_REQUEST['description']) <= 0
            )
            {
                Html::setFlash('必填项目不能为空');
                break;
            }

            $title       = DB::escape($_REQUEST['title']);
            $description = DB::escape($_REQUEST['description']);

            /** 检查是否已存在,防止重复数据 */
            $rbac = RbacService::getInstance();
            $rid  = $rbac->Roles->titleId($title);
            if ($rid > 1)
            {
                Html::setFlash($title . ' 已存在');
                break;
            }

            $rbac->Roles->add($title, $description);
            Html::setFlash('新角色增加成功');
        } while (0);

        $this->redirect($redirect);
        return false;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

