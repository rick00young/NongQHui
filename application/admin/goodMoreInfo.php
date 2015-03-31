<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/19
 * Time: 下午10:59
 */

class goodMoreInfoAction extends BaseAction
{
    public function run($arg = null)
    {

        Yaf_Dispatcher::getInstance()->disableView();
        $shopId = intval(GenerateEncrypt::decrypt($this->get('shop_id'), ID_SIMPLE_KEY));
        if(!$shopId){
            $this->display404();
        }
        $goodId = intval(GenerateEncrypt::decrypt($this->get('good_id'), ID_SIMPLE_KEY));
        if(!$goodId){
            $this->display404();
        }
        $this->assign('shop_id', $this->get('shop_id'));
        $this->assign('good_id', $this->get('good_id'));
        $this->assign('page_title', '完善商品信息');
//        $shop_id = $this->get('shop_id');
//        if(!intval($shop_id)){
//            $this->display404();
//        }
        //$this->assign('shop_id', intval($shop_id));
        $this->getView()->display('admin/goodMoreInfo.phtml');
    }
}