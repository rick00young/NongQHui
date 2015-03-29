<?php
/**
 * @describe:
 * @author: rick
 * */
class save_good_info_Action extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);



        var_export($_POST);die;

        $res = array();
        if($goodId){
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
