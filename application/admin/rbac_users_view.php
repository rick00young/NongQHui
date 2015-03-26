<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class rbac_users_viewAction extends BaseAction
{
    public function run($arg = null)
    {
        //echo __METHOD__;
        $page_size = Html::page_size;
        $user_count = UserModel::getCountFromUser();
        $user_data  = array();
        $pagination = '';

        if ($user_count > 0)
        {
            $page = $this->getRequestParam('cpage', 1) + 0;
            if ($page <= 0)
            {
                $page = 1;
            }
            $pagination = Html::createPager($page, $page_size, $user_count);

            $offset = ($page - 1) * $page_size;
            $user_data = UserModel::getUserData($offset, $page_size);
        }

        $tpl = array(
            'user_count' => $user_count,
            'user_data'  => $user_data,
            'pagination' => $pagination,
        );
        $this->assign('tpl', $tpl);
        $this->getView()->display('admin/rbac_users_view.phtml');
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

