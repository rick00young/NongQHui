<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class editshopAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $shopId = $this->get('shop_id');
        $shopRes = ShopModel::getShopById(intval($shopId));

        if(!$shopRes){
            $this->display404();
        }
        if($this->getUid() != $shopRes['own_id']){
            $this->display404();
        }

        if($shopRes['logo']){
            $imageServer = ImageServer::getInstance();
            $md5Ext = json_decode($shopRes['logo'], true);
            if(is_array($md5Ext)){
                $shopRes['logo_url'] = $imageServer->getThumbUrl($md5Ext['md5'], $md5Ext['ext'], 200, 200, TYPE_NO_BLANK);
            }
        }

        $this->assign('page_title', '修改店铺');
        $this->assign('shop', $shopRes);

        $this->getView()->display('admin/shopinfo.phtml');
    }
}