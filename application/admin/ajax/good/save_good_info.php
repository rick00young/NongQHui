<?php
/**
 * @describe:
 * @author: rick
 * */
class save_good_infoAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);


        $good_intro = $this->post_unescape('good_intro');
        $buy_needKnow = $this->post_unescape('buy_needKnow');
        $buy_detail = $this->post_unescape('buy_detail');
        $use_list = $this->post_unescape('use_list');

        $good_id =  intval(GenerateEncrypt::decrypt($this->post_unescape('good_id'), ID_SIMPLE_KEY));

        if(!$good_id){
            $returnData = HelperResponse::result(HelperResponse::FAIL, 'good_id must be number!', array());
            $this->jsonReturn($returnData);
        }

        $res = array();
        if(!empty($good_intro)){
            $resA = GoodModel::updateOrInsertGoodExInfoByGoodId(array('content' => $good_intro), $good_id, GoodModel::EXT_GOOD_INFO);
        }

        if(!empty($buy_needKnow)){
            $resB = GoodModel::updateOrInsertGoodExInfoByGoodId(array('content' => $buy_needKnow), $good_id, GoodModel::EXT_BUY_NEEDKNOW);
        }

        if(!empty($buy_detail)){
            $resC = GoodModel::updateOrInsertGoodExInfoByGoodId(array('content' => $buy_detail), $good_id, GoodModel::EXT_BUY_DETAIL);
        }

        if(!empty($use_list)){
            $resD = GoodModel::updateOrInsertGoodExInfoByGoodId(array('content' => $use_list), $good_id, GoodModel::EXT_USE_LIST);
        }

        $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'operation success!', $res);
        $this->jsonReturn($returnData);

    }
}
