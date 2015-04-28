<?php
/**
 * @describe:
 * @author: rick
 * */
class saveshopAction extends AdminBaseAction
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
        $shopId = intval($this->post('shop_id', 0));

        $data = array();
        $shopData['name'] = $this->post('shop_name');
        $shopData['slogan'] = $this->post('shop_slogan');
        $shopData['logo'] = $this->post_unescape('shop_logo');
        $shopData['describe'] = $this->post('shop_des');
        $shopData['own_id'] = $own_id;
        $shopData['long'] = $this->post('shop_long');
        $shopData['lat'] = $this->post('shop_lat');
        $shopData['address'] = $this->post('shop_address');
        $shopData['city_id'] = $this->post('shop_city_id');
        $shopData['district_id'] = $this->post('district_id');


        $rules = array(
            'name'=>array('required'=>true,'type'=>'string'),
            'slogan'=>array('type'=>'string'),
            'logo'=>array('type'=>'string'),
            'describe' => array('type'=>'string'),
            'own_id'=>array('required'=>true,'type'=>'number'),
            'long' => array('type'=>'string'),
            'lat'=>array('type'=>'string'),
        );
        $result = HelperValidate::check($shopData, $rules);
        if(!$result){
            $msg = HelperValidate::$errors;
            $returnData = HelperResponse::result(HelperResponse::FAIL, $msg, array());
            $this->jsonReturn($returnData);
        }

        $res = array();
        if($shopId){
            //update
            $updateRes = ShopModel::updateShop($shopData, $shopId);
            if($updateRes){
                $res['shop_id'] = $shopId;
            }
        }else{
            //insert
            $insertRes = ShopModel::createNewShop($shopData);
            if($insertRes){
                $res['shop_id'] = $insertRes;
            }
        }
        if(empty($res)){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'operation fail!', array());
            $this->jsonReturn($returnData);
        }

        $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'operation success!', $res);
        $this->jsonReturn($returnData);

    }
}
