<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/19
 * Time: 下午10:59
 */

class good_editAction extends AdminBaseAction
{
    public function run($arg = null)
    {

        Yaf_Dispatcher::getInstance()->disableView();
        $this->assign('page_title', '编辑商品信息');
        $shop_id = intval(GenerateEncrypt::decrypt($this->get('shop_id'), ID_SIMPLE_KEY));
        $good_id = intval(GenerateEncrypt::decrypt($this->get('good_id'), ID_SIMPLE_KEY));

        if(!$shop_id){
            $this->display404();
        }

        if(!$good_id){
            $this->display404();
        }

        $shopRes = ShopModel::getShopById($shop_id);
        if(!$shopRes || $this->getUid() != $shopRes['own_id']){
            $this->display404();
        }

        $goodRes = GoodModel::getGoodById($good_id);
        if($goodRes){
            $this->assign('good', $goodRes);
        }
        $this->assign('good_id', GenerateEncrypt::encrypt($goodRes['id'], ID_SIMPLE_KEY));
        $this->assign('shop_id', GenerateEncrypt::encrypt($shop_id, ID_SIMPLE_KEY));

        $this->_current_nav = 'shop';
        $this->setCurrentNav();


        $category = array(
            '1' => '农家乐',
            '2' =>'柴鸡',
            '3' => '柴鸡蛋',
            '4' => '樱桃采摘',
            '5' => '桑葚采摘',
            '6' => '苹果采摘',
            '7' => '油桃采摘',
            '8' => '梨采摘',
            '9' => '李子采摘',
            '10'=> '杏采摘',
        );
        $this->assign('category', $category);

        $this->getView()->display('admin/goodinfo.phtml');
    }
}