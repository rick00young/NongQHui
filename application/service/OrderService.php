<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class OrderService
{
    // $business 对应 GoodModel::$category @see: GoodModel
    public static function sCreateOrder($dt, $business)
    {
        $ret = false;

        do {
            if (! isset($dt['product_id']) || $dt['product_id'] <= 0
                || ! isset($dt['producer_uid']) || $dt['producer_uid'] <= 0
                || ! isset($dt['consumer_uid']) || $dt['consumer_uid'] <= 0
                || ! isset($dt['amount']) || -1 == bccomp($dt['amount'], '0.01', 2)
            )
            {
                SeasLog::error(__METHOD__ . ' [LOST PARAMETERS] ' . json_encode($dt));
                break;
            }

            $dt['order_sn'] = OrderModel::createOrderSN($business);
            $dt['status']   = OrderModel::ORDER_STATUS_NO_PAYMENT;
            $dt['create_time'] = time();

            $ret = OrderModel::createOrder($dt);
        } while(0);

        return $ret;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

