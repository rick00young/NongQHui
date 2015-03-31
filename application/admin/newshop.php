<?php
/**
 * @describe:
 * @author: rick
 * */
class newshopAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->assign('page_title', '创建店铺');
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
