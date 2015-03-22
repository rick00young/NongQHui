<?php
/**
 * @describe:
 * @author: rick
 * */
class registerAction extends Yaf_Action_Abstract
{
    public function execute($arg = null)
    {
    	Yaf_Dispatcher::getInstance()->disableView();
    	echo $this->getView()->render('admin/register.phtml');
    }
}
