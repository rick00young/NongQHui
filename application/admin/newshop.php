<?php
/**
 * @describe:
 * @author: rick
 * */
class newshopAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->assign('page_title', '创建店铺');
        $beijingJson = $this->getBeiJingJson();
        $city = json_decode($beijingJson, true);
        $this->assign('city', $city);

        $this->_current_nav = 'shop';
        $this->setCurrentNav();

        $this->getView()->display('admin/shopinfo.phtml');
    }
}
