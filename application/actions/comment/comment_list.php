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
        $goodId = intval(GenerateEncrypt::decrypt($this->get('oid'), ID_SIMPLE_KEY));
        if(!$goodId){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'oid is required!', new stdClass());
            $this->jsonReturn($returnData);
        }

        $otype = PpingCommentModel::COMMENT_TYPE_GOOD;


        $oid = $goodId;
        if(empty($oid)){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'param error', new stdClass());
            $this->jsonReturn($returnData);
        }
        $size = intval($this->get('size')) ?  intval($this->get('size')) : 10 ;
        $max_id = intval($this->get('max_id'));

        $return = array(
            'have_next'=>false,
            'list'=> array(),
            'count' =>0,
        );

        Yaf_loader::import(APPLICATION_PATH . '/contrib/Comment/PpingService.php');

        $return['count'] = PpingService::getCountByOid($otype, $oid);
        if( !$return['count'] ){
            $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success!', $return);
            $this->jsonReturn($returnData);
        }


        $list = PpingService::getListByOid($otype, $oid, array('size'=>$size+1,'max_id'=>$max_id));
        //
        //format
        if(count($list)){
            if(count($list) == $size+1){
                array_pop($list);
                $return['have_next'] = true;
            }
            $return['list'] = $list;

            $parents = $uids = array();
            foreach($return['list'] as $line){
                $uids[] = $line['fuid'];
                if(isset($line['tuid'])){
                    $uids[] = $line['tuid'];
                }
                if(isset($line['parent'])){
                    $parents[] = intval($line['parent']);
                }
            }

            $users = UserModel::getBatchUserInfoByUid($uids);
            $users = !empty($users) ? HelperTool::arrToHashmap($users, 'id') : array();

            $parent_contents = array();
            if(count(array_filter($parents))){
                $parent_contents = PpingService::getCommentByIds($otype, array_filter($parents));
            }

            foreach($return['list'] as &$line){
                $line['_id'] = (string)$line['_id'];
                $line['ancestor'] = (string)$line['ancestor'];
                $line['fuid'] = (string)$line['fuid'];
                $line['oid'] = (string)$line['oid'];
                $line['status'] = (string)$line['status'];
                $line['subcount'] = (string)$line['subcount'];

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
                    if(isset($line['parent']) && !empty($line['parent'])){
                        if(isset($parent_contents[$line['parent']])){
                            $line['to_content'] = $parent_contents[$line['parent']]['content'];
                            $line['to_content_is_del'] = 0;
                        }else{
                            $line['to_content'] = '';
                            $line['to_content_is_del'] = 1;
                        }
                    }
                }else{
                    $line['to_user']  = new stdClass();
                    $line['to_content'] = '';
                    $line['to_content_is_del'] = 0;
                }
                if($this->_islogin && !empty($this->_uid)  && ($line['fuid'] == $this->_uid) ){
                    $line['is_del'] = 1;
                }else{
                    $line['is_del'] = 0;
                }
            }
        }
        $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success!', $return);
        $this->jsonReturn($returnData);
    }
}
