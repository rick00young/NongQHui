<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class logoutAction extends BaseAction
{
    public function run($arg = null)
    {
        if (isset($_SESSION['user_info']))
        {
            unset($_SESSION['user_info']);
        }

        $this->user_info['uid'] = 0;
        $this->user_info['base_info'] = array();

        header('Location: /admin/login');

        Yaf_Dispatcher::getInstance()->disableView();
        return false;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

