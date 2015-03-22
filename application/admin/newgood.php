<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/19
 * Time: 下午10:59
 */

class newgoodAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->assign('page_title', '创建商品');
        $shop_id = $this->get('shop_id');
        if(!intval($shop_id)){
            $this->display404();
        }
        $this->assign('shop_id', intval($shop_id));
        $this->getView()->display('admin/goodinfo.phtml');
    }
}