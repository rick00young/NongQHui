<?php
/**
 * @describe:
 * @author: rick
 * */
class loginAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();

        if ($this->user_info['uid'])
        {
            $this->redirect('/admin');

            return false;
        }
        else
        {
            echo $this->getView()->render('admin/login.phtml');
        }
    }
}
