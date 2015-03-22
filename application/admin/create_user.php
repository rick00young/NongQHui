<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class create_userAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $redirect = '/admin/register';

        if (! isset($_POST) || ! isset($_POST['email']))
        {
            $this->redirect($redirect);
            return false;
        }

        $ret = UserService::createNewUser($_POST);
        if (true === $ret)
        {
            $redirect = '/admin/index';
        }

        $this->redirect($redirect);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

