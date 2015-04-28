<?php
class order_createAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
        if(true !== $this->_islogin){
            $this->display404();
        }

        $goodId = intval(GenerateEncrypt::decrypt($this->post('good_id'), ID_SIMPLE_KEY));
        if(!$goodId){
            $this->display404();
        }

        $goodNum = intval(GenerateEncrypt::decrypt($this->post('good_num'), ID_SIMPLE_KEY));
        if(!$goodNum){
            $this->display404();
        }

        $discountPrice = GenerateEncrypt::decrypt($this->post('discount_price'), ID_SIMPLE_KEY);

        if(!is_numeric($discountPrice)){
            $this->display404();
        }

        $fee = bcmul($discountPrice, $goodNum, 2);

        $goodRes = GoodModel::getGoodInfoByGoodId($goodId);
        if(!$goodRes){
            $this->display404();
        }

        $imageServer = ImageServer::getInstance();
        $photoArr = json_decode($goodRes['photo'], true);
        $photoTemp = array();
        foreach((array)$photoArr as $md5Ext){
            $photoTemp[] = $imageServer->getThumbUrl($md5Ext['md5'], $md5Ext['ext'], 200, 200, TYPE_NO_BLANK);
        }
        $goodRes['photo'] = $photoTemp;

        $realFee = bcmul($goodRes['discount_price'], $goodNum, 2);
        if($discountPrice != $goodRes['discount_price'] || $fee != $realFee){
            SeasLog::error('price problem  fee:' . $fee . ' realFee:' . $realFee);
        }

        $uid = $this->getUid();
        $order_dt = array(
            'product_id' => $goodId,
            'producer_uid' => 1,
            'consumer_uid' => $uid,
            'amount' => $realFee,
        );
        $orderRes = OrderService::sCreateOrder($order_dt, $goodRes['category_id']);
        if(!$orderRes){
            $this->display404();
        }

        $orderInfo = OrderModel::getOrderDataById($orderRes);

        $this->assign('good', $goodRes);
        //$this->assign('order_no', $orderRes);
        $this->assign('order', $orderInfo);

        $this->getView()->display('second_view/order_pay.phtml');
    }
}