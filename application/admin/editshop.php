<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class editshopAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $shopId = intval(GenerateEncrypt::decrypt($this->get('shop_id'), ID_SIMPLE_KEY));
        if(!$shopId){
            $this->display404();
        }

        $shopRes = ShopModel::getShopById($shopId);

        if(!$shopRes){
            $this->display404();
        }
        if($this->getUid() != $shopRes['own_id']){
            $this->display404();
        }

        if($shopRes['logo']){
            $imageServer = ImageServer::getInstance();
            $md5Ext = json_decode($shopRes['logo'], true);
            if(is_array($md5Ext)){
                $shopRes['logo_url'] = $imageServer->getThumbUrl($md5Ext['md5'], $md5Ext['ext'], 200, 200, TYPE_NO_BLANK);
            }
        }

        $this->assign('page_title', '修改店铺');
        $this->assign('shop', $shopRes);

        $beijingJson = '{
        "id": "01",
        "name": "北京",
        "city": {
          "id": "0101",
          "name": "北京",
          "county": [
            {
              "id": "010101",
              "name": "北京",
              "weatherCode": "101010100"
            },
            {
              "id": "010102",
              "name": "海淀",
              "weatherCode": "101010200"
            },
            {
              "id": "010103",
              "name": "朝阳",
              "weatherCode": "101010300"
            },
            {
              "id": "010104",
              "name": "顺义",
              "weatherCode": "101010400"
            },
            {
              "id": "010105",
              "name": "怀柔",
              "weatherCode": "101010500"
            },
            {
              "id": "010106",
              "name": "通州",
              "weatherCode": "101010600"
            },
            {
              "id": "010107",
              "name": "昌平",
              "weatherCode": "101010700"
            },
            {
              "id": "010108",
              "name": "延庆",
              "weatherCode": "101010800"
            },
            {
              "id": "010109",
              "name": "丰台",
              "weatherCode": "101010900"
            },
            {
              "id": "010110",
              "name": "石景山",
              "weatherCode": "101011000"
            },
            {
              "id": "010111",
              "name": "大兴",
              "weatherCode": "101011100"
            },
            {
              "id": "010112",
              "name": "房山",
              "weatherCode": "101011200"
            },
            {
              "id": "010113",
              "name": "密云",
              "weatherCode": "101011300"
            },
            {
              "id": "010114",
              "name": "门头沟",
              "weatherCode": "101011400"
            },
            {
              "id": "010115",
              "name": "平谷",
              "weatherCode": "101011500"
            }
          ]
        }
      }';
        $city = json_decode($beijingJson, true);
        $this->assign('city', $city);

        $this->getView()->display('admin/shopinfo.phtml');
    }
}