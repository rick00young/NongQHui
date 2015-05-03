<?php
Yaf_loader::import(APPLICATION_PATH . '/contrib/Comment/Idgenerator.php');
Yaf_loader::import(APPLICATION_PATH . '/contrib/Comment/PpingStruct.php');

class PpingCommentModel
{
    const COLLECTION_NAME = 'comment_%s';
    const DB_NAME = 'pping';

    const COMMENT_TYPE_GOOD = 'good';//商品评论

    /**
     * 添加一条评论
     * @param block
     * @param oid
     * @param content
     *
     * @return
     */
    public function add($block, $oid, Array $content)
    {
        $comment = array();
        $comment['_id'] = Idgenerator::incrementMongoId($block);
        $comment['oid'] = $oid;
        $collectionName  = $this->getCollection($block);
        $comment = array_merge( $comment, PpingStruct::getComment($content));

        if(!isset($comment['parent'])){
            $comment['ancestor'] = $comment['_id'];
            $comment['subcount'] = 0;
        }
        $comment['status'] = 1;
        $comment['in_time'] = new MongoDate();
        if ( ! PpingStruct::validateComment($comment)) {
            return false;
        }

        $re = $this->mongo(self::DB_NAME)->$collectionName->insert($comment);
        if($re && is_array($re) ){
            return $comment;
        }
        return $re;
    }

    public function getComment($block,$_id){
        if(empty($_id)){
            return false;
        }
        $collectionName  = $this->getCollection($block);
        $instance = $this->mongo(self::DB_NAME);

        $seq = $instance->$collectionName->findOne(array('_id' =>intval($_id)));
        return $seq;
    }
    public function incrSubCount($block,$_id){
        if(empty($_id)){
            return;
        }
        $collectionName =  $this->getCollection($block);
        $instance = $this->mongo(self::DB_NAME);
        $step = 1;
        $seq = $instance->command(array(
            'findAndModify' => $collectionName,
            'query'         => array('_id' => $_id),
            'update'        => array('$inc' => array('subcount' => $step )),
            'new'           => true,
        ));
        return true;
    }
    /**
     * 获取一个帖子下面的评论列表
     * @param block
     * @param oid
     * @param option
     * @return
     */
    public function getListByOid($block, $oid, $option = array())
    {
        $option['max_id'] = empty($option['max_id']) ? 0 : $option['max_id'];
        $option['size'] = empty($option['size']) ? 10 : $option['size'];

        $collectionName = $this->getCollection($block);

        $query = array('oid' => $oid);
        if(!empty($option['max_id'])){
            $query['_id'] = array('$lt' => $option['max_id']);
        }
        $query['status'] = 1;
        $res = $this->mongo(self::DB_NAME)->$collectionName->find($query)->sort(array('_id' => -1))->limit($option['size']);
        //
        $list = array();
        foreach ($res as $info) {
            $list[] = $info;
        }
        return $list;
    }
    public function getCountByOid($block, $oid, $option = array())
    {
        $collectionName = $this->getCollection($block);
        $count = $this->mongo(self::DB_NAME)->$collectionName->count(array('oid' => intval($oid),"status"=>1));
        return $count;
    }
    public function updateCommentStatus($block, $_id, $status){
        $_id = intval($_id);
        $info = $this->getComment($block, $_id);
        if($status == $info['status']){
            return 0;       //未更新
        }
        $collectionName = $this->getCollection($block);
        return $this->mongo(self::DB_NAME)->$collectionName->update(array("_id" =>$_id ), array('$set' => array("status" => $status)));
    }

    protected function getCollection($block){
        return  sprintf(self::COLLECTION_NAME, Idgenerator::getBlockId($block));
    }

    public function addCommentQueue($block, $comment){
        $includeFile = HelperConfig::get("queue_bootstrap_file");
        require_once($includeFile);
        try {
            $channel = CHANNEL_EXP_COMMENT_SENSITIVE_DETECT;

            $arr = array(
                'id'      =>  (string)$comment['_id'],
                'content' =>  $comment['content'],
                'ip'      =>  HelperHttp::getClientIp(),
                'block'   =>  $block,
            );
            $msg = json_encode($arr);
            $client = \Rquque\Core\Producer\Client::getInstance();
            $res = $client->publish($channel, $msg);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return true;
    }

    public function getByIds($block,$ids){
        if(!is_array($ids) || empty($ids)){
            return false;
        }
        $collectionName  = $this->getCollection($block);
        $instance = $this->mongo(self::DB_NAME);
        $query = array(
            '_id'   =>  array('$in'=> $ids),
            'status'    =>  1,
        );
        $seq = $instance->$collectionName->find($query);
        $list = array();
        foreach ($seq as $info) {
            $list[$info['_id']] = $info;
        }
        return $list;
    }

    public function delComment($block,$_id){
        if(empty($_id)){
            return false;
        }
        $collectionName  = $this->getCollection($block);
        $instance = $this->mongo(self::DB_NAME);
        $seq = $instance->$collectionName->remove(array("_id" => intval($_id)));
        return $seq;
    }

    public function decrSubCount($block,$_id){
        if(empty($_id)){
            return;
        }
        $collectionName =  $this->getCollection($block);
        $instance = $this->mongo(self::DB_NAME);
        $step = -1;
        $seq = $instance->command(array(
            'findAndModify' => $collectionName,
            'query'         => array('_id' => $_id),
            'update'        => array('$inc' => array('subcount' => $step )),
            'new'           => true,
        ));
        return true;
    }

    protected function mongo($dbname){
        return DbMongo::getInstance()->$dbname;
    }

    public static function getInstance(){
        return new PpingCommentModel();
    }

}
