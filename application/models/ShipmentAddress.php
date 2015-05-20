<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 上午9:45
 */

class ShipmentAddressModel {
    const TABLE_NAME = 'shipment_address';

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

    /**
     * 创建新地址
     * @param $saveData
     * @return bool
     */

    public static function createShipmentAddress($saveData){
        $goodData['add_time'] = time();
        DB::insert($saveData, self::TABLE_NAME);
        return DB::lastInsertId();
    }
    /**
     * 更新地址
     * @param $saveData
     * @param $addressId
     * @return bool
     */

    public static function updateShipmentAddress($saveData, $addressId){
        return DB::update($saveData, self::TABLE_NAME, $addressId);
    }

    /*
    public static function getShipmentAddress($goodId, $option = null)
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
    */

    /**
     * 获取用户收货地址
     * @param $uid
     * @return array or false
     */
    public static function getShipmentAddressByUid($uid){
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `uid` = "%s" ', intval($uid));
        //echo $sql . PHP_EOL;exit;
        return DB::getOne($sql);
    }

    /*
    public static function delShipmentAddressByAddressId($addressId){
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `uid` = "%s" ', intval($uid));
        //echo $sql . PHP_EOL;exit;
        return DB::getOne($sql);
    }
    */



}
