<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class PaymentRequestModel
{
    const TABLE_NAME = 'payment_request';

    public static function createPaymentRequest($data)
    {
        return DB::insert($data, self::TABLE_NAME);
    }

    public static function getPaymentRequestById($payment_request_id)
    {
        $sql = sprintf('SELECT * FROM `%s` WHERE id = %d LIMIT 1', self::TABLE_NAME, $payment_request_id);
        return DB::getOne($sql);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

