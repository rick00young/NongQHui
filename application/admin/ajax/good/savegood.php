<?php
/**
 * @describe:
 * @author: rick
 * */
class savegoodAction extends AdminBaseAction
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
        if($this->post('good_id')){
            $goodId = intval(GenerateEncrypt::decrypt($this->post('good_id'), ID_SIMPLE_KEY));
            if(!$goodId){
                $returnData = HelperResponse::result(HelperResponse::FAIL, 'good_id must be number!', array());
                $this->jsonReturn($returnData);
            }
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


        $data = array();
        $goodData['title'] = $this->post('good_title');
        $goodData['slogan'] = $this->post('good_slogan');
        $goodData['price'] = $this->post_unescape('good_price');

        $price = is_numeric($goodData['price']) ? $goodData['price'] : 0;
        $goodData['real_price'] = sprintf('%.2f',$price);

        $discount_price = is_numeric($this->post('good_d_price')) ? $this->post('good_d_price') : 0;
        $goodData['discount_price'] = sprintf('%.2f', $discount_price);

        $goodData['stock'] = intval($this->post('good_stock'));
        $goodData['status'] = 1;
        $goodData['shop_id'] = $shop_id;
        $goodData['district_id'] = $shopRes['district_id'];
        $goodData['contactor'] = $this->post('good_contactor');
        $goodData['phone'] = $this->post('good_phone');
        $goodData['unit'] = $this->post('good_unit');
        $goodData['category_id'] = $this->post('good_category_id');


        $rules = array(
            'title'=>array('required'=>true,'type'=>'string'),
            'slogan'=>array('required' => true,'type'=>'string'),
            'price'=>array('required' => true,'type'=>'string'),
            'discount_price' => array('required' => true,'type'=>'string'),
            'shop_id'=>array('required'=>true,'type'=>'number'),
        );
        $result = HelperValidate::check($goodData, $rules);
        if(!$result){
            $msg = HelperValidate::$errors;
            $returnData = HelperResponse::result(HelperResponse::FAIL, $msg, array());
            $this->jsonReturn($returnData);
        }

        $res = array();
        if(isset($goodId) && $goodId){
            //update
            $updateRes = GoodModel::updateGood($goodData, $goodId);
            if($updateRes){
                $res['good_id'] = $goodId;
            }
        }else{
            //insert
            $insertRes = GoodModel::createNewGood($goodData);

            if($insertRes){
                $res['good_id'] = $insertRes;
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
