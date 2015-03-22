<?php
/**
 * @describe:
 * @author: rick
 * */
class newshopAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->assign('page_title', '创建店铺');
        $this->getView()->display('admin/shopinfo.phtml');
    }
}
