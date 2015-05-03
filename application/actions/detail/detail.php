<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class detailAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $goodId = intval(GenerateEncrypt::decrypt($this->get('good_id'), ID_SIMPLE_KEY));

        if(!$goodId){
            $this->display404();
        }

        $goodRes = GoodModel::getGoodById($goodId);
        if(!$goodRes){
            $this->display404();
        }

        $shopId = $goodRes['shop_id'];

        $shopRes = ShopModel::getShopById($shopId);
        if(!$shopRes){
            $this->display404();
        }

        $goodRes['order_url'] = '/index/order_create';
        $goodRes['encode_good_id'] = GenerateEncrypt::encrypt($goodRes['id'], ID_SIMPLE_KEY);

        $goodExtInfo = GoodModel::getGoodALLExInfoByGoodId($goodId);

        $imageServer = ImageServer::getInstance();

        foreach($goodExtInfo as $info) {
            if($info['type'] == GoodModel::EXT_GOOD_INFO){
                $photoArr = json_decode($info['content'], true);
                $photoTemp = array();
                foreach((array)$photoArr as $md5Ext){
                    $photoTemp[] = $imageServer->getThumbUrl($md5Ext['md5'], $md5Ext['ext'], 748, 500, TYPE_FIX_WIDTH);
                }
                $goodRes['photo'] = $photoTemp;
            }
            if($info['type'] == GoodModel::EXT_BUY_NEEDKNOW){
                $goodRes['buy_needKnow'] = $info['content'];
            }
            if($info['type'] == GoodModel::EXT_BUY_DETAIL){
                $goodRes['buy_detail'] = $info['content'];
            }
            if($info['type'] == GoodModel::EXT_USE_LIST){
                $goodRes['use_list']  = $info['content'];
            }
        }

        //var_export($goodRes);
        //var_export($shopRes);
        
        //share
        $share = array();
        $share['title'] = '北京五月樱桃节! 北京周边采摘!'.htmlspecialchars($goodRes['title']).' '.htmlspecialchars($goodRes['slogan']).'; '.$shopRes['name'].'; 地址: '.$shopRes['address'];
        $share['url'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $share['pic'] = $goodRes['photo'][0];

        $this->assign('share', $share);

        $this->assign('good', $goodRes);
        $this->assign('shop', $shopRes);

        $this->assign('recommend', array());

        Widget::increasePV($goodId); // 自增 PV
        $this->assign('visit_pv', Widget::getPV($goodId));

        //TKD
        $this->_page_title = $goodRes['title'];
        $this->_page_description = $goodRes['title'] . '--' . $goodRes['slogan'];
        $this->_page_keywords = $goodRes['title'] . '--' . $goodRes['slogan'];
        $this->setTDK();

        $this->assign('is_mobile',$this->isMobilePlatform());
        $this->assign('_current_nav', 'detail');

        $this->getView()->display('second_view/detail.phtml');
    }

}
