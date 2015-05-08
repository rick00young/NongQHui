<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/5/8
 * Time: 下午6:15
 */

require_once realpath(dirname(__FILE__)) .'/../../script_init.php';



$orderId = 0;
$timeExpire = 3600*2;

do {

    $orderStatus = array(OrderModel::ORDER_STATUS_NO_PAYMENT);
    $sql = sprintf("select `id`, `status`, `create_time` from `%s`
                    where id > '%d' and `status` in (%s)
                    order by id limit 100",
        'order', $orderId, implode(',', $orderStatus));


    $orderRes = DB::getAll($sql);

    if(!$orderRes){
        $orderId = 0;
    }
    foreach($orderRes as $order){
        if($order['status'] == OrderModel::ORDER_STATUS_NO_PAYMENT && $order['create_time'] >= $timeExpire){
            //TODO 取消定单
            //TODO 库存加1
        }
        $orderId = $order['id'];
    }
    var_dump($sql);

} while($orderId);





