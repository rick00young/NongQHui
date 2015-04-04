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
        $uid = $this->getUid();
        $shopRes= ShopModel::getShopByIdAndUid($shopId, $uid);
        if(!$shopRes){
            $this->display404();
        }

        $goodRes = GoodModel::getGoodsByShopId($shopId);

        if($goodRes){
            foreach($goodRes as &$good){
                $good['online'] = '/admin/good_on_line?shop_id='.$shopId.'&good_id=' . GenerateEncrypt::encrypt($good['id'],ID_SIMPLE_KEY);
                $good['offline'] = '/admin/good_off_line?shop_id='.$shopId.'&good_id=' . GenerateEncrypt::encrypt($good['id'],ID_SIMPLE_KEY);
                $good['delete'] = '/admin/good_delete?shop_id='.$shopId.'&good_id=' . GenerateEncrypt::encrypt($good['id'],ID_SIMPLE_KEY);
                $good['edit'] = '/admin/good_edit?shop_id='.$shopId.'&good_id=' . GenerateEncrypt::encrypt($good['id'],ID_SIMPLE_KEY);
                $good['edit_info'] = '/admin/goodmoreinfo?shop_id='.$shopId.'&good_id=' . GenerateEncrypt::encrypt($good['id'],ID_SIMPLE_KEY);
                $good['preview'] = '/admin/good_preview?shop_id='.$shopId.'&good_id=' . GenerateEncrypt::encrypt($good['id'],ID_SIMPLE_KEY);
            }

        }

        $this->assign('goods', $goodRes);
        $this->assign('shop', $shopRes);

        $this->getView()->display('admin/goodList.phtml');
    }
} 