<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class shoplistAction extends BaseAction
{
    public function run($arg = null)
    {
        $uid = $this->getUid();
        $shopRes = ShopModel::getShopsByUid(intval($uid));
        //var_export($shopRes);

        $shopsInfo = array();
        $imageServer = ImageServer::getInstance();
        foreach($shopRes as &$value){
            $md5Ext = json_decode($value['logo'], true);
            if(is_array($md5Ext)){
                $value['logo'] = $imageServer->getThumbUrl($md5Ext['md5'], $md5Ext['ext'], 200, 200, TYPE_NO_BLANK);
                $value['href'] = '/admin/editshop?shop_id=' . $value['id'];
            }

        }
        unset($value);
        $this->assign('shops', $shopRes);

    	$this->getView()->display('admin/shoplist.phtml');
    }
}
