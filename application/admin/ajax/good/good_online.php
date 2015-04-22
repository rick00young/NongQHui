<?php
/**
 * @describe:
 * @author: rick
 * */
class good_onlineAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);

        $own_id = $this->getUid();
        if(!intval($own_id)){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'user identity error!', array());
            //TODO 用户身份验证
            $this->jsonReturn($returnData);
        }

        $goodId = intval(GenerateEncrypt::decrypt($this->post('good_id'), ID_SIMPLE_KEY));
        if(!$goodId){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'good_id must be number!', array());
            $this->jsonReturn($returnData);
        }


        $shop_id = intval(GenerateEncrypt::decrypt($this->post('shop_id'), ID_SIMPLE_KEY));
        if(!$shop_id){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'shop_id must be number!', array());
            $this->jsonReturn($returnData);
        }
        $shopRes = ShopModel::getShopById($shop_id);
        if(!$shopRes){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'cat not find shop_id = '.$shop_id.'!', array());
            $this->jsonReturn($returnData);
        }

        if($shopRes['own_id'] != $own_id){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'you dont have right to do this!', array());
            $this->jsonReturn($returnData);
        }

        $goodRes = GoodModel::getGoodById($goodId);
        if(!$goodRes || $goodRes['shop_id'] != $shop_id){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'you dont have right to do this yet!', array());
            $this->jsonReturn($returnData);
        }


        $goodData = array();
        $goodData['status'] = 1;//上线

        $res = array();

        //update
        $updateRes = GoodModel::updateGood($goodData, $goodId);
        if($updateRes){
            $res['good_id'] = $goodId;
        }

        if(empty($res)){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'operation fail!', array());
            $this->jsonReturn($returnData);
        }

        $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'operation success!', $res);
        $this->jsonReturn($returnData);

    }
}
