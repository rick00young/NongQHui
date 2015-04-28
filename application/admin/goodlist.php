<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/19
 * Time: 下午10:59
 */

class goodlistAction extends AdminBaseAction{
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

        $goodRes = GoodModel::getGoodsByShopId($shopId, array('status'=>array(1,2)));

        if($goodRes){
            foreach($goodRes as &$good){
                $encodeGoodId = GenerateEncrypt::encrypt($good['id'],ID_SIMPLE_KEY);
                $encodeShopId = GenerateEncrypt::encrypt($shopId,ID_SIMPLE_KEY);

                $good['encode_shop_id'] = $encodeShopId;
                $good['encode_good_id'] = $encodeGoodId;
                $good['online'] = '/admin/good_on_line?shop_id='.$encodeShopId.'&good_id=' . $encodeGoodId;
                $good['offline'] = '/admin/good_off_line?shop_id='.$encodeShopId.'&good_id=' . $encodeGoodId;
                $good['delete'] = '/admin/good_delete?shop_id='.$encodeShopId.'&good_id=' . $encodeGoodId;
                $good['edit'] = '/admin/good_edit?shop_id='.$encodeShopId.'&good_id=' . $encodeGoodId;
                $good['edit_info'] = '/admin/goodmoreinfo?shop_id='.$encodeShopId.'&good_id=' . $encodeGoodId;
                $good['preview'] = '/admin/good_preview?shop_id='.$encodeShopId.'&good_id=' . $encodeGoodId;
            }

        }

        $this->assign('goods', $goodRes);
        $this->assign('shop', $shopRes);

        $this->_current_nav = 'shop';
        $this->setCurrentNav();

        $this->getView()->display('admin/goodList.phtml');
    }
} 