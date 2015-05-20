<?php
class order_confirmAction extends BaseAction
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
        /*
        $goodNum = intval(GenerateEncrypt::decrypt($this->post('good_num'), ID_SIMPLE_KEY));
        if(!$goodNum){
            $this->display404();
        }
        $discountPrice = GenerateEncrypt::decrypt($this->post('discount_price'), ID_SIMPLE_KEY);
        if(!is_numeric($discountPrice)){
            $this->display404();
        }
        $fee = bcmul($discountPrice, $goodNum, 2);
        */

        $uid = $this->getUid();
        $goodRes = GoodModel::getGoodInfoByGoodId($goodId);

        if(!$goodRes){
            $this->display404();
        }

        //库存不足
        if($goodRes['stock'] <= 0){
            SeasLog::error(__METHOD__ . ' [库存不足]: good_id:' . $goodId);
            $this->display404();
        }

        //是否限购
        $canBuy = SaleStrategyService::canBuyThisProduct($uid, $goodId);
        if(!$canBuy){
            SeasLog::error(__METHOD__ . ' [限购]: good_id:' . $goodId);
            $this->display404();
        }

        $imageServer = ImageServer::getInstance();
        $photoArr = json_decode($goodRes['photo'], true);
        $photoTemp = array();
        foreach((array)$photoArr as $md5Ext){
            $photoTemp[] = $imageServer->getThumbUrl($md5Ext['md5'], $md5Ext['ext'], 200, 200, TYPE_NO_BLANK);
        }
        $goodRes['photo'] = $photoTemp;


        $this->assign('good', $goodRes);

        $this->getView()->display('second_view/order_confirm.phtml');
    }
}