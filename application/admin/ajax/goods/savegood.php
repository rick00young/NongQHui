<?php
/**
 * @describe:
 * @author: rick
 * */
class savegoodAction extends BaseAction
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
        $goodId = intval($this->post('shop_id', 0));

        $data = array();
        $goodData['name'] = $this->post('shop_name');
        $goodData['slogan'] = $this->post('shop_slogan');
        $goodData['logo'] = $this->post_unescape('shop_logo');
        $goodData['describe'] = $this->post('shop_des');
        $goodData['own_id'] = $own_id;
        $goodData['long'] = $this->post('shop_long');
        $goodData['lat'] = $this->post('shop_lat');

        $rules = array(
            'name'=>array('required'=>true,'type'=>'string'),
            'slogan'=>array('type'=>'string'),
            'logo'=>array('type'=>'string'),
            'describe' => array('type'=>'string'),
            'own_id'=>array('required'=>true,'type'=>'number'),
            'long' => array('type'=>'string'),
            'lat'=>array('type'=>'string'),
        );
        $result = HelperValidate::check($goodData, $rules);
        if(!$result){
            $msg = HelperValidate::$errors;
            $returnData = HelperResponse::result(HelperResponse::FAIL, $msg, array());
            $this->jsonReturn($returnData);
        }

        $res = array();
        if($goodId){
            //update
            $updateRes = ShopModel::updateShop($goodData, $goodId);
            if($updateRes){
                $res['shop_id'] = $goodId;
            }
        }else{
            //insert
            $insertRes = ShopModel::createNewShop($goodData);
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
