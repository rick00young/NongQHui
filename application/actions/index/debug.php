<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class debugAction extends Yaf_Action_Abstract
{
    public function execute($arg = null)
    {
        //echo REGISTER_MODEL_BUSINESS;

        // $conf = Yaf_Registry::get('config')->cookie->toArray();
        // var_export($conf);

        // $conf = Yaf_Registry::get('config')->cookie->domain;
        // var_export($conf);

        //redirect('http://www.baidu.com');
        //$db = DB::getOne('select * from shop');
        //var_export($db);

        //$rbac = new \PhpRbac\Rbac();
        //$all_roles =  $rbac->Users->allRoles(1);
        //print_r($all_roles);

        //Html::autoVersion('/static/admin/assets/js/ace.min.js');

        //$user_info = UserModel::getUserInfoByUid(1);
        //print_r($user_info);

        //UserModel::deleteUserByUid(2);

        //header("X-Accel-Redirect: /test/a.png");

        //$order_dt = array(
        //    'product_id' => 2,
        //    'producer_uid' => 1,
        //    'consumer_uid' => 123,
        //    'amount' => '32.58',
        //);
        //$ret = OrderService::sCreateOrder($order_dt, '02');
        //print_r($ret);

        //// 二维码数据
        //$data = 'http://www.agrovips.com';

        //// 生成的文件名
        //$filename = false;

        //// 纠错级别：L、M、Q、H
        //$errorCorrectionLevel = 'L';

        //// 点的大小：1到10
        //$matrixPointSize = 4;
        //QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        //$sql = 'select * from comment';
        //var_export(PGDB::getAll($sql));

        //'pgsql:dbname=rate_comment;host=127.0.0.1;port=5432;user=rick;password=rick'
//        $dbh = new PDO('pgsql:dbname=rate_comment;
//                           host=127.0.0.1;
//                           user=rick;
//                           password=rick');
        //var_export($dbh->query($sql)->fetch());

        //INSERT INTO comment (parent_id, obj_type, obj_id, uid, nickname, avator, content, status, in_time, up_time) VALUES ('0', '4', '4', '5', 'rick',  '545', '534', '1', '1430359048', '1430359048');
        /*
        $save = array (
            'parent_id' => 0,
            'obj_type' => 4,
            'obj_id' => 4,
            'uid' => 5,
            'nickname' => 'rick',
            'avator' => '545',
            'content' => '534',
            'status' => 1,
            'in_time' => time(),
            'up_time' => time(),
        );
        CommentModel::createComment($save);

        //Yaf_loader::import(APPLICATION_PATH . '/contrib/Comment/SnsOauth.php');

        Yaf_loader::import(APPLICATION_PATH . '/contrib/Comment/PpingService.php');


        $comment = array();
        $comment['fuid'] = intval(10);
        $comment['oid'] = intval(20);
        $comment['parent'] = intval(0);
        $comment['content'] =  trim('comment');
        //$comment['trans'] =  trim($this->post("trans"));

        //check trans
        /*
        if ($comment['trans'] != md5(mb_substr($comment['content'], 0 ,1))) {
            $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success', '');
            $this->jsonReturn($returnData);
        }
        */
        /*
        if(empty($comment['fuid']) || empty($comment['oid']) || empty($comment['content'])){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'param error', new stdClass());
            $this->jsonReturn($returnData);
        }
        if(mb_strwidth($comment['content'],'UTF-8') > 900 ){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'param error', new stdClass());
            $this->jsonReturn($returnData);
        }
        $re = PpingService::create('good', $comment['oid'] , $comment);
        if(!$re){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'fail', new stdClass());
            $this->jsonReturn($returnData);
        }
        $line = $re;
        $uids = array($line['fuid']);
        if(isset($line['tuid'])){
            $uids[]= $line['tuid'];
        }

        $line['_id'] = (string)$line['_id'];
        $line['ancestor'] = (string)$line['ancestor'];
        $line['in_time'] =  HelperTool::nicetime($line['in_time']->sec);
        $line['from_user'] = isset($users[$line['fuid']]) ? $users[$line['fuid']]: new ArrayObject();
        if(empty($line['from_user']['avatar'])){
            $line['from_user']['avatar'] = '';
        }
        if(isset($line['tuid']) &&  isset($users[$line['tuid']])){
            $line['to_user'] =  $users[$line['tuid']];
            if(empty($line['to_user']['avatar'])){
                $line['to_user']['avatar'] = '';
            }
        }else{
            $line['to_user']  = new stdClass();
        }
        $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success', $line);
        $this->jsonReturn($returnData);
        */

        $oid = intval(20);
        if(empty($oid)){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'param error', new stdClass());
            $this->jsonReturn($returnData);
        }
        $size = 10 ;
        $max_id = intval(0);

        $return = array(
            'have_next'=>false,
            'list'=> array(),
            'count' =>0,
        );

        $return['count'] = PpingService::getCountByOid('good',$oid);
        if( !$return['count'] ){
            $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success!', $return);
            $this->jsonReturn($returnData);
        }

        $list = PpingService::getListByOid('good',$oid, array('size'=>$size+1,'max_id'=>$max_id));
        //var_export($list);die;
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

            //$users = UserModel::getBatchUserInfoByUid($uids);
            $users = array('10' => array('uid' => 10));
            $parent_contents = array();
            if(count(array_filter($parents))){
                $parent_contents = PpingService::getCommentByIds("good",array_filter($parents));
            }
            foreach($return['list'] as &$line){
                $line['_id'] = (string)$line['_id'];
                $line['ancestor'] = (string)$line['ancestor'];
                $line['in_time'] =  HelperTool::nicetime($line['in_time']->sec);
                $line['from_user'] = '';//isset($users[$line['fuid']]) ? $users[$line['fuid']]: new ArrayObject();
                if(empty($line['from_user']['avatar'])){
                    $line['from_user']['avatar'] = '';
                }
                if(isset($line['tuid']) &&  isset($users[$line['tuid']])){
                    $line['to_user'] =  $users[$line['tuid']];
                    if(empty($line['to_user']['avatar'])){
                        $line['to_user']['avatar'] = '';
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
                if( isset($this->_isLogin) ){
                    $line['is_del'] = 1;
                }else{
                    $line['is_del'] = 0;
                }
            }
        }
        $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'success!', $return);
        $this->jsonReturn($returnData);


        return false;
    }

    protected function jsonReturn($data){
        echo json_encode($data);
    }
}
