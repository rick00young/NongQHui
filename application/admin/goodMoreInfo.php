<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/19
 * Time: 下午10:59
 */

class goodMoreInfoAction extends BaseAction
{
    public function run($arg = null)
    {

        Yaf_Dispatcher::getInstance()->disableView();
        $shopId = intval(GenerateEncrypt::decrypt($this->get('shop_id'), ID_SIMPLE_KEY));
        if(!$shopId){
            $this->display404();
        }
        $goodId = intval(GenerateEncrypt::decrypt($this->get('good_id'), ID_SIMPLE_KEY));
        if(!$goodId){
            $this->display404();
        }
        $this->assign('shop_id', $this->get('shop_id'));
        $this->assign('good_id', $this->get('good_id'));
        $this->assign('page_title', '完善商品信息');

        $good_intro = GoodModel::getGoodALLExInfoByGoodId($goodId);
        //var_export($good_intro);
        $good = array();
        foreach($good_intro as $info){
            if($info['type'] == GoodModel::EXT_GOOD_INFO){
                $good['good_intro'] = $info['content'];
            }
            if($info['type'] == GoodModel::EXT_BUY_NEEDKNOW){
                $good['buy_needKnow'] = $info['content'];
            }
            if($info['type'] == GoodModel::EXT_BUY_DETAIL){
                $good['buy_detail'] = $info['content'];
            }
            if($info['type'] == GoodModel::EXT_USE_LIST){
                $good['use_list']  = $info['content'];
            }
        }
        $this->assign('good', $good);
        $this->getView()->display('admin/goodMoreInfo.phtml');
    }
}