<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class PaymentResponseModel
{
    const TABLE_NAME = 'payment_response';

    public static function createPaymentResponse($data)
    {
        return DB::insert($data, self::TABLE_NAME);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

