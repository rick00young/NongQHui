<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/19
 * Time: 下午10:59
 */

class good_editAction extends BaseAction
{
    public function run($arg = null)
    {

        Yaf_Dispatcher::getInstance()->disableView();
        $this->assign('page_title', '编辑商品信息');
        $shop_id = intval(GenerateEncrypt::decrypt($this->get('shop_id'), ID_SIMPLE_KEY));
        $good_id = intval(GenerateEncrypt::decrypt($this->get('good_id'), ID_SIMPLE_KEY));

        if(!$shop_id){
            $this->display404();
        }

        if(!$good_id){
            $this->display404();
        }

        $shopRes = ShopModel::getShopById($shop_id);
        if(!$shopRes || $this->getUid() != $shopRes['own_id']){
            $this->display404();
        }

        $goodRes = GoodModel::getGoodById($good_id);
        if($goodRes){
            $this->assign('good', $goodRes);
        }

        $this->assign('shop_id', GenerateEncrypt::encrypt($shop_id, ID_SIMPLE_KEY));

        $this->_current_nav = 'shop';
        $this->setCurrentNav();

        $this->getView()->display('admin/goodinfo.phtml');
    }
}