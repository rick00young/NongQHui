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
        $goodId = intval(GenerateEncrypt::decrypt($_GET['good_id'], ID_SIMPLE_KEY));

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

        $goodExtInfo = GoodModel::getGoodALLExInfoByGoodId($goodId);

        $imageServer = ImageServer::getInstance();

        foreach($goodExtInfo as $info){
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

        $this->assign('good', $goodRes);
        $this->assign('shop', $shopRes);

        $this->getView()->display('second_view/detail.phtml');
    }
}