<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class comment_addAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        if(true !== $this->_islogin){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'please login first', new stdClass());
            $this->jsonReturn($returnData);
        }

        $goodId = intval(GenerateEncrypt::decrypt($this->post('oid'), ID_SIMPLE_KEY));
        if(!$goodId){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'oid is required!', new stdClass());
            $this->jsonReturn($returnData);
        }

        $parent = intval(GenerateEncrypt::decrypt($this->post('parent'), ID_SIMPLE_KEY));

        $otype = PpingCommentModel::COMMENT_TYPE_GOOD;

        /////
        $comment = array();
        $comment['fuid'] = intval($this->_uid);
        $comment['oid'] = $goodId;
        $comment['parent'] = $parent;
        $comment['content'] =  trim($this->post("content"));
        $comment['trans'] =  trim($this->post("trans"));

        //check trans
        /*
        if ($comment['trans'] != md5(mb_substr($comment['content'], 0 ,1))) {
            $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success', '');
            $this->jsonReturn($returnData);
        }
        */
        if(empty($comment['fuid']) || empty($comment['oid']) || empty($comment['content'])){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'param error', new stdClass());
            $this->jsonReturn($returnData);
        }
        if(mb_strwidth($comment['content'],'UTF-8') > 900 ){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'param error', new stdClass());
            $this->jsonReturn($returnData);
        }

        Yaf_loader::import(APPLICATION_PATH . '/contrib/Comment/PpingService.php');
        $re = PpingService::create($otype, $comment['oid'] , $comment);
        if(!$re){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'fail', new stdClass());
            $this->jsonReturn($returnData);
        }
        $line = $re;
        $uids = array($line['fuid']);
        if(isset($line['tuid'])){
            $uids[]= $line['tuid'];
        }
        $users = UserModel::getBatchUserInfoByUid(array_filter($uids));
        $users = is_array($users) ? HelperTool::arrToHashmap($users, 'id') : array();

        $line['_id'] = (string)$line['_id'];
        $line['ancestor'] = (string)$line['ancestor'];
        $line['in_time'] =  HelperTool::nicetime($line['in_time']->sec);
        $line['from_user'] = isset($users[$line['fuid']]) ? $users[$line['fuid']]: new ArrayObject();

        unset($line['from_user']['email']);
        unset($line['from_user']['last_login_time']);
        unset($line['from_user']['register_model']);
        unset($line['from_user']['register_time']);
        unset($line['from_user']['deleted']);
        unset($line['from_user']['id']);

        if(empty($line['from_user']['avator'])){
            $line['from_user']['avator'] = C_FE_GUEST_AVATAR_68x68;
        }
        if(isset($line['tuid']) &&  isset($users[$line['tuid']])){
            $line['to_user'] =  $users[$line['tuid']];
            if(empty($line['to_user']['avator'])){
                $line['to_user']['avator'] = C_FE_GUEST_AVATAR_68x68;
            }
        }else{
            $line['to_user']  = new stdClass();
        }
        $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success', $line);
        $this->jsonReturn($returnData);
    }
}
