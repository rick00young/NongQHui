<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/19
 * Time: 下午10:59
 */

class goodlistAction extends BaseAction{
    public function run($arg = null)
    {
        $uid = $this->getUid();
        $shopId = intval(GenerateEncrypt::decrypt($this->get('shop_id'), ID_SIMPLE_KEY));

        if(!$shopId){
            $this->display404();
        }

        $shopRes= ShopModel::getShopById($shopId);

        $goodRes = GoodModel::getGoodsByShopId($shopId);


        $this->assign('good', $goodRes);
        $this->assign('shop', $shopRes);

        $this->getView()->display('admin/goodList.phtml');
    }
} 