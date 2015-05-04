<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class meAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        $this->assign('_current_nav_', 'me');
        if(true != $this->_islogin){
            $this->redirect('/sns/login');
        }
        $this->canApplySeller();

        $this->getView()->display('second_view/me.phtml');
    }
}