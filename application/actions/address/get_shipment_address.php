<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class get_shipment_addressAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        $id = $this->get('id');
        $operation = $this->get('operation');
        if(!$operation){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'param error', '');
            $this->jsonReturn($returnData);
        }
        $data = '';
        if('get_province' == $operation){
            Yaf_loader::import(APPLICATION_PATH . '/conf/address/province.php');
            $data = array_values($data);
        }

        if('get_city' == $operation){
            Yaf_loader::import(APPLICATION_PATH . '/conf/address/city.php');
            $city = isset($data[$id]) ? $data[$id] : array();
            $city['list'] = array_values($city['list']);
            $data = $city;
        }

        if('get_district' == $operation){
            Yaf_loader::import(APPLICATION_PATH . '/conf/address/district.php');
            $city = isset($data[$id]) ? $data[$id] : array();
            $city['list'] = array_values($city['list']);
            $data = $city;
        }


        if($data){
            $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success', $data);
        }else{
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'fail', '');
        }

        $this->jsonReturn($returnData);
    }
}
