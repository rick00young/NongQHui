<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class save_shipment_addressAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        //$id = $this->get('id');
        /*var query = {
                    'receiver':receiver,
                    'province':province,
                    'city':city,
                    'district':district,
                    'address':address,
                    'mobile':mobile,
                    'email':email
                };
         * */
        $saveData = array(
                    'receiver' => $this->post('receiver'),
                    'province' => $this->post('province'),
                    'city' => $this->post('city'),
                    'district' => $this->post('district'),
                    'address' => $this->post('address'),
                    'mobile' => $this->post('mobile'),
                    'email' => $this->post('email'),
        );
        $rules = array(
            'receiver'=>array('required'=>true,'type'=>'string'),
            'province'=>array('required'=>true,'type'=>'string'),
            'city'=>array('required'=>true,'type'=>'string'),
            'district' => array('required'=>true,'type'=>'string'),
            'address'=>array('required'=>true,'type'=>'string'),
            'mobile' => array('required'=>true,'type'=>'mobile'),
            'email'=>array('required'=>true,'type'=>'email'),
        );
        $result = HelperValidate::check($saveData, $rules);
        if(!$result){
            $msg = HelperValidate::$errors;
            $returnData = HelperResponse::result(HelperResponse::FAIL, $msg, array());
            $this->jsonReturn($returnData);
        }

        $saveData['uid'] = $this->getUid();
        $saveData['status'] = 1;
        $saveData['add_time'] = time();
        $addressId = intval($this->post('id'));

        $res = array();
        if($addressId){
            $saveRes = ShipmentAddressModel::updateShipmentAddress($saveData, $addressId);
            if($saveRes){
                $res['id'] = $addressId;
            }
        }else{
            $saveRes = ShipmentAddressModel::createShipmentAddress($saveData);
            if($saveRes){
                $res['id'] = $saveRes;
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
