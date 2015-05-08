<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class SaleStrategyService
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

    public static function getProdutSaleStrategyByProductId($productId){
        if(!intval($productId)) return false;
        $ret = SaleStrategyModel::getSaleStrategyByProductId(intval($productId));

        if(!$ret) return false;
        return $ret;
    }

    /**
     * 此商品限购,查看用户是否可以购买
     * @param $uid
     * @param $productId
     * @param $saleStrategy 销售策略数据
     * @return bool
     */
    public static function canBuyThisProduct($uid, $productId, $saleStrategy = array()){
        if(empty($saleStrategy)){
            $saleStrategy = self::getProdutSaleStrategyByProductId($productId);
        }

        //没有销售策略,正常购买
        if(!is_array($saleStrategy)) return true;

        $cacheKey = 'buyer_limit_' . $uid;
        $redis = RedisDB::getInstance();

        $count = intval($redis->hGet($cacheKey, $productId));
        if(0 == $count) return true;//没有记录可以购买

        if($count >= $saleStrategy['buyer_limit']){
            return false;//大于限购数量,不可以再购买
        }
        return true;
    }

    /**
     * 此商品限购,付款后对此用户的商品购买加一标记
     * @param $uid
     * @param $productId
     * @param $saleStrategy 销售策略数据
     * @return null
     */
    public static function incrBuyerLimit($uid, $productId, $saleStrategy = array()){
        if(empty($saleStrategy)){
            $saleStrategy = self::getProdutSaleStrategyByProductId($productId);
        }
        if(empty($saleStrategy)) return false;

        if(SaleStrategyModel::SALE_STRATEGY_TYPE_LIMIT_BUYER == $saleStrategy['strategy_type']){
            $cacheKey = 'buyer_limit_' . $uid;
            $redis = RedisDB::getInstance();
            $redis->hIncrBy($cacheKey, $productId, 1);
            return true;
        }
        return false;

    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

