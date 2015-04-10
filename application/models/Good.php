<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 上午9:45
 */

class GoodModel {
    const TABLE_NAME = 'good';
    const EXT_INFO_TABLE = 'good_ext_info';
    //类别:1为采摘,2为柴鸡蛋,3为农家乐,0未知
    const FARM = 1;
    const EGG = 2;
    const YARD = 3;
    const UNKNOWN = 0;

    //内容类型.1.产品介绍, 2:订单详情, 3:购买须知, 4.使用流程
    const EXT_GOOD_INFO = 1;
    const EXT_BUY_DETAIL = 2;
    const EXT_BUY_NEEDKNOW = 3;
    const EXT_USE_LIST = 4;

    public static function createNewGood($goodData){
        $goodData['add_time'] = time();
        DB::insert($goodData, self::TABLE_NAME);
        return DB::lastInsertId();
    }

    public static function updateGood($goodData, $goodId){
        return DB::update($goodData, self::TABLE_NAME, $goodId, array('logo'));
    }

    public static function getGoodsByShopId($goodId, $status = 'all')
    {
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `shop_id` = "%s" ', DB::escape($goodId));
        if('all' != $status){
            $sql .= sprintf(" AND status = '%d'", intval($status));
        }
        //echo $sql . PHP_EOL;exit;

        return DB::getAll($sql);
    }

    public static function getGoodById($goodId){
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `id` = "%s" ', DB::escape($goodId));
        //echo $sql . PHP_EOL;exit;
        return DB::getOne($sql);
    }

    public static function updateOrInsertGoodExInfoByGoodId($data, $goodId, $type){
        $exInfoRes = self::getGoodExInfoByGoodId($goodId, $type);
        if($exInfoRes){
            return self::updateGoodExInfoByGoodId($data, $exInfoRes['id']);
        }
        $data['type'] = $type;
        $data['good_id'] = $goodId;
        $data['status'] = 1;
        return self::createGoodExInfo($data);
    }

    public static function getGoodExInfoByGoodId($goodId, $type){
        $sql  = sprintf('SELECT * FROM `%s` ', self::EXT_INFO_TABLE);
        $sql .= sprintf('WHERE `good_id` = "%d" and type = "%d"', DB::escape($goodId), DB::escape($type));
        //echo $sql . PHP_EOL;exit;
        return DB::getOne($sql);
    }

    public static function getGoodALLExInfoByGoodId($goodId){
        $sql  = sprintf('SELECT * FROM `%s` ', self::EXT_INFO_TABLE);
        $sql .= sprintf('WHERE `good_id` = "%d"', DB::escape($goodId));
        //echo $sql . PHP_EOL;exit;
        return DB::getAll($sql);
    }

    public static function updateGoodExInfoByGoodId($data, $id){
        return DB::update($data, self::EXT_INFO_TABLE, $id, array('content'));
    }

    public static function createGoodExInfo($data){
        $data['add_time'] = time();
        DB::insert($data, self::EXT_INFO_TABLE, array('content'));
        return DB::lastInsertId();
    }

    public static function getGoodCountWithShopIds($shopIds, $isOnline = 1){
        $sql  = sprintf('SELECT shop_id, count(*) as count FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `shop_id` in (%s)', DB::escape(implode(',', $shopIds)));
        if('all' == $isOnline){

        }else{
            $sql .= sprintf(' and `status` = %d', intval($isOnline));
        }
        $sql .= sprintf(' group by shop_id');

        return DB::getAll($sql);
    }
} 