<?php
//require_once('');
class PpingService
{
    public static function create($block, $oid, Array $content)
    {
        try {
            if(isset($content['parent'])  && !empty($content['parent'])){
                // get ancestor
                $info =  PpingCommentModel::getInstance()->getComment($block,$content['parent']);
                if(!is_array($info)  || empty($info)){
                    return false;
                }
                $content['tuid'] = intval($info['fuid']);
                $content['ancestor'] = intval($info['ancestor']);
            }

            $re = PpingCommentModel::getInstance()->add($block, $oid, $content);
            if ($re) {
                // incr  ancestor subcount
                if( isset($re['ancestor']) && !empty($re['ancestor']) && !empty($re['parent'])){
                    PpingCommentModel::getInstance()->incrSubCount($block,$re['ancestor']);
                }
                $re['to_content'] = isset($info) ? $info['content'] : '';

                //现在没有内容过滤
                //PpingCommentModel::getInstance()->addCommentQueue($block, $re);
                return $re;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //todo log
        }
        return false;
    }

    public static function getCommentById($block, $_id)
    {
        return PpingCommentModel::getInstance()->getComment($block, $_id);
    }
    public static function getCommentByIds($block, $ids)
    {
        return PpingCommentModel::getInstance()->getByIds($block, $ids);
    }

    public static function getListByOid($block, $oid, $option = array())
    {
        return PpingCommentModel::getInstance()->getListByOid($block, $oid, $option);
    }

    public static function getCountByOid($block, $oid, $option = array())
    {
        return PpingCommentModel::getInstance()->getCountByOid($block, $oid, $option);
    }

    public static function updateCommentStatus($block, $_id, $status=1){
        $info = self::getCommentById($block,$_id);
        if(!is_array($info) || empty($info)){
            return false;
        }
        $re = PpingCommentModel::getInstance()->updateCommentStatus($block,$_id,$status);
        if( $re === true  &&  (string)$info['_id'] != (string)$info['ancestor'] ){
            //减小 subcount
            PpingCommentModel::getInstance()->decrSubCount($block,$info['ancestor']);
        }
        return  ($re!==false) ?  true : false;
    }

    public static function delComment($block, $_id){
        $info = self::getCommentById($block,$_id);
        if(!is_array($info) || empty($info)){
            return false;
        }
        $re = PpingCommentModel::getInstance()->delComment($block,$_id);
        if($re &&  (string)$info['_id'] != (string)$info['ancestor'] ){
            //减小 subcount
            PpingCommentModel::getInstance()->decrSubCount($block,$info['ancestor']);
        }
        return $re;
    }

}
