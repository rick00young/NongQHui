<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 上午9:45
 */

class ShopModel {
    const TABLE_NAME = 'shop';

    public static function createNewShop($shopData){
        $shopData['add_time'] = time();
        DB::insert($shopData, self::TABLE_NAME, array('logo'));
        return DB::lastInsertId();
    }

    public static function updateShop($shopData, $shopId){
        return DB::update($shopData, self::TABLE_NAME, $shopId, array('logo'));
    }

    public static function getShopsByUid($uid)
    {
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `own_id` = "%s" ', DB::escape($uid));
        //echo $sql . PHP_EOL;exit;

        return DB::getAll($sql);
    }

    public static function getShopById($shopId){
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `id` = "%s" ', DB::escape($shopId));
        //echo $sql . PHP_EOL;exit;

        return DB::getOne($sql);
    }

    public static function getShopByIdAndUid($shopId, $uid){
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `id` = "%d" and own_id = "%d"', DB::escape($shopId), intval($uid));
        //echo $sql . PHP_EOL;exit;

        return DB::getOne($sql);
    }
} 