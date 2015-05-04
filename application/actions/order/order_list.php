<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/4/21
 * Time: 下午10:31
 */

class order_listAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        if(true !== $this->_islogin){
            $this->display404();
        }

        $uid = $this->getUid();

        $this->canApplySeller();

        $orderStatus = intval(GenerateEncrypt::decrypt($this->get('status'), ID_SIMPLE_KEY));
        if(!$orderStatus){
            $orderStatus= 0;
        }
        $orderRes = OrderModel::getOrderByeUid($uid, 0, 100, $orderStatus);

        if(!$orderRes){
            $this->getView()->display('second_view/order_list.phtml');
            return;
        }
        $goodIds = array();
        foreach($orderRes['list'] as $order){
            $goodIds[] = $order['product_id'];
        }
        unset($order);

        $goodRes = GoodModel::getGoodsByGoodId($goodIds);
        $goodRes = is_array($goodRes) ? $goodRes : array();
        $goodRes = HelperTool::arrToHashmap($goodRes, 'id');

        $extIds = array();
        foreach($goodRes as $good){
            if(!$good['head_img']){
                $extIds[] = $good['id'];
            }
        }

        $extRes = GoodModel::getGoodsExInfoByGoodId($extIds, GoodModel::EXT_GOOD_INFO);
        $extRes = is_array($extRes) ? $extRes : array();
        $extRes = HelperTool::arrToHashmap($extRes,'good_id');

        $imageServer = ImageServer::getInstance();

        foreach($orderRes['list'] as $key => &$order){
            if(isset($goodRes[$order['product_id']])){
                $order['title'] = $goodRes[$order['product_id']]['title'];
            }
            //先去good数据里找头图
            if( isset($goodRes[$order['product_id']]) && $goodRes[$order['product_id']]['head_img'] ){
                $img = json_decode($goodRes[$order['product_id']]['head_img'], true);
                if(!empty($img)){
                    $order['head_img'] = $imageServer->getThumbUrl($img['md5'],$img['ext'], 200, 200, TYPE_NO_BLANK);
                }
            }
            //若good里没有头图,再去扩展数据里找
            if( !isset($order['head_img']) && isset($extRes[$order['product_id']]) && $extRes[$order['product_id']]['type'] == GoodModel::EXT_GOOD_INFO){
                $imgs = json_decode($extRes[$order['product_id']]['content'], true);
                $imgs = is_array($imgs) ? $imgs : array();
                foreach($imgs as $img){
                    if($img['md5'] && $img['ext']){
                        $order['head_img'] = $imageServer->getThumbUrl($img['md5'],$img['ext'], 200, 200, TYPE_NO_BLANK);
                        break;
                    }
                }
            }

            $order['payment_handle'] = $this->handlePayStatus($order['status'], $order);

        }
        unset($order);

        $this->assign('orders', $orderRes);
        $this->getView()->display('second_view/order_list.phtml');
    }

    protected function handlePayStatus($status, $order){

        $result = array();
        if($status == OrderModel::ORDER_STATUS_NO_PAYMENT){
            $result['title'] = '立即支付';
            $result['pay_url'] = '/index/pay';
        }else if($status == OrderModel::ORDER_STATUS_NO_CONSUME){
            $result['title'] = '未消费';
        }else if($status == OrderModel::ORDER_STATUS_CONSUMED){
            $result['title'] = '已消费';
        }else if($status == OrderModel::ORDER_STATUS_REFUND){
            $result['title'] = '已退款';
        }
        //TODO 已取消
        return $result;
    }

}