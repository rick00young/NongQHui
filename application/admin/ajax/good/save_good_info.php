<?php
/**
 * @describe:
 * @author: rick
 * */
class save_good_infoAction extends BaseAction
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
        $resA = GoodModel::updateOrInsertGoodExInfoByGoodId(array('content' => $good_intro), $good_id, GoodModel::EXT_GOOD_INFO);
        $resB = GoodModel::updateOrInsertGoodExInfoByGoodId(array('content' => $buy_needKnow), $good_id, GoodModel::EXT_BUY_NEEDKNOW);
        $resC = GoodModel::updateOrInsertGoodExInfoByGoodId(array('content' => $buy_detail), $good_id, GoodModel::EXT_BUY_DETAIL);
        $resD = GoodModel::updateOrInsertGoodExInfoByGoodId(array('content' => $use_list), $good_id, GoodModel::EXT_USE_LIST);


        $returnData = HelperResponse::result(HelperResponse::SUCCESS, 'operation success!', $res);
        $this->jsonReturn($returnData);

    }
}
