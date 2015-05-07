<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class SaleStrategyModel
{
    const TABLE_NAME = 'sale_strategy';

    //策略类型
    const SALE_STRATEGY_TYPE_LIMIT = 0;//没有策略
    const SALE_STRATEGY_TYPE_LIMIT_BUYER = 1;//用户限购,如一个用户可购几个同样的产品
    const SALE_STRATEGY_TYPE_LIMIT_TIME = 2;//限时促销,某个时间段内可购买某个产品


    /*
    private static $order_status = array(
        self::ORDER_STATUS_NO_PAYMENT => '未支付',
        self::ORDER_STATUS_NO_CONSUME => '未消费',
        self::ORDER_STATUS_CONSUMED   => '已消费',
        self::ORDER_STATUS_REFUND     => '已退款',
    );

    public static function displayOrderStatus($status)
    {
        $display = '';
        if (isset(self::$order_status[$status]))
        {
            $display = self::$order_status[$status];
        }

        return $display;
    }

    //创建销售策略
    public static function createSaleStrategy($data){
        $data['add_time'] = time();
        DB::insert($data, self::TABLE_NAME);
        SeasLog::debug(__METHOD__ . ' [data]: ' . json_encode($data));
        return DB::lastInsertId();
    }
    */

    //replace into sql 创建新销售策略
    public static function createSaleStrategy($data){
        $data['add_time'] = time();
        DB::replaceInto($data, self::TABLE_NAME);
        SeasLog::debug(__METHOD__ . ' [data]: ' . json_encode($data));
        return DB::lastInsertId();
    }

    public static function getSaleStrategyByProductId($productId){
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `product_id` = "%s" and status = 1', DB::escape($productId));
        //echo $sql . PHP_EOL;exit;

        return DB::getOne($sql);
    }


}
/* vi:set ts=4 sw=4 et fdm=marker: */

