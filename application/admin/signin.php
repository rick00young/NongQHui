<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class signinAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $redirect = '/admin/login';

        do
        {
            if (! isset($_POST) || ! isset($_POST['email']) || ! isset($_POST['password']))
            {
                Html::setFlash('请勿非法提交');
                break;
            }

            if ('' == trim($_POST['email']) || '' == trim($_POST['password']))
            {
                Html::setFlash('电子邮箱和密码不能为空');
                break;
            }

            if (! UserService::verifyAccount($_POST['email'], $_POST['password']))
            {
                break;
            }
        }
        while (0);

        $this->redirect($redirect);
        return false;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

