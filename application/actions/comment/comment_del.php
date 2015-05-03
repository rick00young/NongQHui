<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class comment_listAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $id = intval(GenerateEncrypt::decrypt($this->post('_id'), ID_SIMPLE_KEY));
        if(!$id){
            $returnData = HelperResponse::result(HelperResponse::FAIL, '_id is required!', new stdClass());
            $this->jsonReturn($returnData);
        }

        if(true !== $this->_islogin){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'please login first', new stdClass());
            $this->jsonReturn($returnData);
        }

        $otype = PpingCommentModel::COMMENT_TYPE_GOOD;

        $_id = $id;
        $info = PpingService::getCommentById($otype, $_id);

        if(empty($info) || !is_array($info)){
            $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success!', new stdClass());
            $this->jsonReturn($returnData);
        }
        if($this->_uid != $info['fuid']){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'auth fail', new stdClass());
            $this->jsonReturn($returnData);
        }
        $re = PpingService::delComment($otype, $_id);
        if($re){
            $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success!', new stdClass());
            $this->jsonReturn($returnData);
        }else{
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'system error', new stdClass());
            $this->jsonReturn($returnData);
        }
    }
}
