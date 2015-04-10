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
        $beijingJson = $this->getBeiJingJson();
        $city = json_decode($beijingJson, true);
        $this->assign('city', $city);
        $this->getView()->display('admin/shopinfo.phtml');
    }
}
