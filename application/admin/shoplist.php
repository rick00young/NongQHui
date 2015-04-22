<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class shoplistAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $uid = $this->getUid();
        $shopRes = ShopModel::getShopsByUid(intval($uid));
        //var_export($shopRes);

        $shopsInfo = array();
        $imageServer = ImageServer::getInstance();

        //$goodCount = GoodModel::getGoodCountWithShopId()
        if($shopRes){
            $shopIds = HelperTool::arrayColumn($shopRes, 'id');
        }

        $goodCounts = array();
        if(!empty($shopIds)){
            $goodCounts = GoodModel::getGoodCountWithShopIds($shopIds, 'all');
            $goodCounts = HelperTool::arrToHashmap($goodCounts, 'shop_id');
        }

        foreach($shopRes as &$value){
            $md5Ext = json_decode($value['logo'], true);
            if(is_array($md5Ext)){
                $value['logo'] = $imageServer->getThumbUrl($md5Ext['md5'], $md5Ext['ext'], 200, 200, TYPE_NO_BLANK);
            }

            $value['good_count'] = isset($goodCounts[$value['id']]) ? $goodCounts[$value['id']]['count'] : 0;

            $encodeId = GenerateEncrypt::encrypt($value['id'], ID_SIMPLE_KEY);
            $value['href'] = '/admin/editshop?shop_id=' . $encodeId;
            $value['good_list'] = '/admin/goodlist?shop_id=' . $encodeId;
            $value['new_good'] = '/admin/newgood?shop_id=' . $encodeId;

        }
        unset($value);
        //var_export($shopRes);
        $this->assign('shops', $shopRes);

        $this->_current_nav = 'shop';
        $this->setCurrentNav();

    	$this->getView()->display('admin/shoplist.phtml');
    }
}
