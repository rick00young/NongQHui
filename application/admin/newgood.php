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
        $shop_id = intval(GenerateEncrypt::decrypt($this->get('shop_id'), ID_SIMPLE_KEY));

        if(!$shop_id){
            $this->display404();
        }
        $this->assign('shop_id', GenerateEncrypt::encrypt($shop_id, ID_SIMPLE_KEY));
        $this->getView()->display('admin/goodinfo.phtml');
    }
}