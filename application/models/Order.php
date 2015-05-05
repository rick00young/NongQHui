<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class OrderModel
{
    const PRIMARY_TABLE_NAME = 'order';
    const LOG_TABLE_NAME     = 'order_log';

    // 订单状态
    const ORDER_STATUS_NO_PAYMENT = 1;
    const ORDER_STATUS_NO_CONSUME = 2;
    const ORDER_STATUS_CONSUMED   = 3;
    const ORDER_STATUS_REFUND     = 4;

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

    // 订单操作日志
    private static function log($order_id, $op_uid, $log_data)
    {
        $save_data = array(
            'order_id' => $order_id,
            'op_uid'   => $op_uid,
            'create_time' => time(),
            'log' => json_encode($log_data),
        );

        return DB::insert($save_data, self::LOG_TABLE_NAME, array('log'));
    }

    public static function getOrderDataById($order_id)
    {
        $sql  = sprintf('SELECT * FROM `%s` ', self::PRIMARY_TABLE_NAME);
        $sql .= sprintf('WHERE `id` = "%d" ', $order_id);
        $sql .= 'LIMIT 1';

        SeasLog::debug(__METHOD__ . ' [SQL]: ' . $sql);
        return DB::getOne($sql);
    }

    public static function getOrderDataBySN($order_sn)
    {
        $sql  = sprintf('SELECT * FROM `%s` ', self::PRIMARY_TABLE_NAME);
        $sql .= sprintf('WHERE `order_sn` = "%s" ', DB::escape($order_sn));
        $sql .= 'LIMIT 1';

        SeasLog::debug(__METHOD__ . ' [SQL]: ' . $sql);
        return DB::getOne($sql);
    }

    // 生成订单编号
    public static function createOrderSN($business)
    {
        while (1)
        {
            $order_sn = sprintf('%s%02d%d', date('ymdH'), $business, mt_rand(100000, 999999));
            $order_dt = self::getOrderDataBySN($order_sn);

            // 不存在撞单
            if (! is_array($order_dt) || count($order_dt) <= 0)
            {
                break;
            }
        }

        return $order_sn;
    }

    /** 调用者自行控制数据安全与有效 */
    public static function createOrder($save_data)
    {
        $lastInsertId =  DB::insert($save_data, self::PRIMARY_TABLE_NAME);
        self::log($lastInsertId, $save_data['consumer_uid'], $save_data);

        return $lastInsertId;
    }

    public static function updateOrder($update_data, $id, $op_uid)
    {
        DB::update($update_data, self::PRIMARY_TABLE_NAME, $id);
        self::log($id, $op_uid, $update_data);

        return $id;
    }

    /*此方法主要用于前台的个人订单中心及管理后台的管理员订单管理中心*/
    public static function getOrderByConsumerUid($uid, $start = 0, $size = 0, $status = 0, $option = array()){
        $sql  = sprintf('SELECT SQL_CALC_FOUND_ROWS * FROM `%s` ', self::PRIMARY_TABLE_NAME);

        if(!empty($option) && isset($option['role']) && $option['role'] == 'admin'){
            $sql .= sprintf('WHERE `consumer_uid` > "%d" ', 0);
        }else{
            $sql .= sprintf('WHERE `consumer_uid` = "%d" ', $uid);
        }

        if(intval($status)){
            $sql .= sprintf(' and `status` = "%d" ', intval($status));
        }

        $limit = array();
        if(intval($start)){
            array_push($limit, intval($start));
        }
        if(intval($size)){
            array_push($limit, intval($size));
        }

        if(!empty($limit)){
            $sql .= sprintf(' LIMIT %s', implode(',', $limit));
        }

        SeasLog::debug(__METHOD__ . ' [SQL]: ' . $sql);
        $orders =  DB::getAll($sql);
        if(!$orders){
            return $orders;
        }
        $count = DB::foundRows();

        return array('list' => $orders, 'count' => $count);
    }

    /*此方法主要用于管理后台的商户订单管理中心*/
    public static function getOrderByProducerUid($uid, $start = 0, $size = 0, $status = 0, $option = array()){
        $sql  = sprintf('SELECT SQL_CALC_FOUND_ROWS * FROM `%s` ', self::PRIMARY_TABLE_NAME);

        if(!empty($option) && isset($option['role']) && $option['role'] == 'admin'){
            $sql .= sprintf('WHERE `producer_uid` > "%d" ', 0);
        }else{
            $sql .= sprintf('WHERE `producer_uid` = "%d" ', $uid);
        }

        if(intval($status)){
            $sql .= sprintf(' and `status` = "%d" ', intval($status));
        }

        $limit = array();
        if(intval($start)){
            array_push($limit, intval($start));
        }
        if(intval($size)){
            array_push($limit, intval($size));
        }

        if(!empty($limit)){
            $sql .= sprintf(' LIMIT %s', implode(',', $limit));
        }

        SeasLog::debug(__METHOD__ . ' [SQL]: ' . $sql);
        $orders =  DB::getAll($sql);
        if(!$orders){
            return $orders;
        }
        $count = DB::foundRows();

        return array('list' => $orders, 'count' => $count);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

