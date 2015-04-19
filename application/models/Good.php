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

    //内容类型.1.产品介绍, 2:订单详情, 3:购买须知, 4.使用流程
    const EXT_GOOD_INFO = 1;
    const EXT_BUY_DETAIL = 2;
    const EXT_BUY_NEEDKNOW = 3;
    const EXT_USE_LIST = 4;

    //类别
    const UNKNOWN   = 0;
    const FARM      = 1;
    const CHICKEN   = 2;
    const EGG       = 3;
    const CHERRY    = 4;
    const MULBERRY  = 5;
    const APPLE     = 6;
    const NECTARINE = 7;
    const PEAR      = 8;
    const PLUM      = 9;
    const APRICOT   = 10;

    private static $category = array(
        self::UNKNOWN   => '未知',
        self::FARM      => '农家乐',
        self::CHICKEN   =>'柴鸡',
        self::EGG       => '柴鸡蛋',
        self::CHERRY    => '樱桃采摘',
        self::MULBERRY  => '桑葚采摘',
        self::APPLE     => '苹果采摘',
        self::NECTARINE => '油桃采摘',
        self::PEAR      => '梨采摘',
        self::PLUM      => '李子采摘',
        self::APRICOT   => '杏采摘',
    );

    public static function getCategory()
    {
        return self::$category;
    }

    public static function createNewGood($goodData){
        $goodData['add_time'] = time();
        DB::insert($goodData, self::TABLE_NAME);
        return DB::lastInsertId();
    }

    public static function updateGood($goodData, $goodId){
        return DB::update($goodData, self::TABLE_NAME, $goodId, array('logo'));
    }

    public static function getGoodsByShopId($goodId, $option = null)
    {
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `shop_id` = "%s" ', DB::escape($goodId));
        if(is_array($option) && !empty($option['status'])){
            $sql .= sprintf(" AND status in (%s)", implode(',', $option['status']));
        }else{
            $sql .= sprintf(" AND status in (%s)", implode(',', array(1)));
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


    /***use for index**/
    public static function getGoodBydDstrictId($districtId){
        if(empty($districtId)){
            return false;
        }
        $sql = sprintf("SELECT g.id,g.title,g.shop_id, g.slogan, g.price, g.unit,
        s.address, s.district_id,
        ext.content
        FROM good AS g
        LEFT JOIN shop AS s ON g.shop_id = s.id
        LEFT JOIN good_ext_info AS ext ON ext.good_id = g.id
        WHERE g.status = 1 AND s.district_id = '%s' AND ext.type = 1
        ORDER BY g.up_time DESC
        LIMIT 4", $districtId);

        return DB::getAll($sql);
    }

}
