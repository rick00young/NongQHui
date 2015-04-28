<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/19
 * Time: 下午10:59
 */

class order_listAction extends AdminBaseAction{
    public function run($arg = null)
    {

        $uid = $this->getUid();
        $pageSize = 10;
        $orderStatus = intval(GenerateEncrypt::decrypt($this->get('status'), ID_SIMPLE_KEY));
        if(!$orderStatus){
            $orderStatus= 0;
        }
        $this->assign('current_status', $orderStatus);

        $page = intval($this->get('cpage'));
        if($page <= 0){
            $page = 1;
        }
        $start = ($page - 1) * $pageSize;
        $orderRes = OrderModel::getOrderByeUid($uid, $start, $pageSize, $orderStatus, array('role' => 'admin'));

        $orderRes['list'] = isset($orderRes['list']) ? $orderRes['list'] : array();
        $orderRes['count'] = isset($orderRes['count']) ? $orderRes['count'] : 0;

        $orderRes['page_current'] = $page;
        $orderRes['page_size'] = $pageSize;

        $goodIds = array();
        $uids = array();
        foreach($orderRes['list'] as $order){
            $goodIds[] = $order['product_id'];
            $uids [] = $order['consumer_uid'];
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

        //consumer
        $consumerRes = UserModel::getBatchUserInfoByUid($uids);
        $consumerRes = is_array($consumerRes) ? $consumerRes : array();
        $consumerRes = HelperTool::arrToHashmap($consumerRes, 'id');

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
            if( isset($consumerRes[$order['consumer_uid']]) ){
                $order['user_info'] = $consumerRes[$order['consumer_uid']];
            }

            $order['payment_handle'] = $this->handlePayStatus($order['status'], $order);

            $order['encode_uid'] = GenerateEncrypt::encrypt($order['consumer_uid'], ID_SIMPLE_KEY);

        }
        unset($order);



        //$pp = Html::createPager($page, $pageSize, $orderRes['count']);
        $pp = Html::CcreatePager($page,  $pageSize, $orderRes['count']);
        $this->assign('pp', $pp);
        $this->assign('orders', $orderRes);

        $this->assign('order_status',array(
            array(
                'value' => OrderModel::ORDER_STATUS_NO_PAYMENT,
                'name' => OrderModel::displayOrderStatus(OrderModel::ORDER_STATUS_NO_PAYMENT),
                'url' => '/admin/order_list?status='. GenerateEncrypt::encrypt(OrderModel::ORDER_STATUS_NO_PAYMENT, ID_SIMPLE_KEY),
            ),
            array(
                'value' => OrderModel::ORDER_STATUS_NO_CONSUME,
                'name' => OrderModel::displayOrderStatus(OrderModel::ORDER_STATUS_NO_CONSUME),
                'url' => '/admin/order_list?status='. GenerateEncrypt::encrypt(OrderModel::ORDER_STATUS_NO_CONSUME, ID_SIMPLE_KEY),
            ),
            array(
                'value' => OrderModel::ORDER_STATUS_CONSUMED,
                'name' => OrderModel::displayOrderStatus(OrderModel::ORDER_STATUS_CONSUMED),
                'url' => '/admin/order_list?status='. GenerateEncrypt::encrypt(OrderModel::ORDER_STATUS_CONSUMED, ID_SIMPLE_KEY),
            ),
            array(
                'value' => OrderModel::ORDER_STATUS_REFUND,
                'name' => OrderModel::displayOrderStatus(OrderModel::ORDER_STATUS_REFUND),
                'url' => '/admin/order_list?status='. GenerateEncrypt::encrypt(OrderModel::ORDER_STATUS_REFUND, ID_SIMPLE_KEY),
            ),
        ));

        $this->getView()->display('admin/order_list.phtml');
    }

    protected function handlePayStatus($status, $order){

        $result = array();
        if($status == OrderModel::ORDER_STATUS_NO_PAYMENT){
            $result['title'] = '未支付';
            $result['pay_url'] = '/index/pay';
        }else if($status == OrderModel::ORDER_STATUS_NO_CONSUME){
            $result['title'] = '未消费';
        }else if($status == OrderModel::ORDER_STATUS_CONSUMED){
            $result['title'] = '已消费';
        }else if($status == OrderModel::ORDER_STATUS_REFUND){
            $result['title'] = '已退款';
        }
        //已取消
        return $result;
    }

    protected function handlePage($orders){

    }
} 