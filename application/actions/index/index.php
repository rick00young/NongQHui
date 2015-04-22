<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class indexAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);

        $cacheKey = 'vips_home';
        $districtIds = array('010102', '010107');
        $imageServer = ImageServer::getInstance();
        $redis = RedisCache::getInstance();

        $cache = $redis->get($cacheKey);

        $response = json_decode($cache, true);
        if(empty($response)){
            foreach($districtIds as $district_id){
                $goodRes = GoodModel::getGoodBydDstrictId($district_id);
                if(is_array($goodRes) && !empty($goodRes)){
                    foreach($goodRes as &$good){
                        if($good['content']){
                            $photoA = json_decode($good['content'], true);
                            $photo = array();
                            foreach($photoA as $md5Ext){
                                $img = $imageServer->getThumbUrl($md5Ext['md5'], $md5Ext['ext'],450, 300, TYPE_NO_BLANK);
                                if($img){
                                    $photo[] = $img;
                                    break;
                                }
                            }
                            $good['detail_url'] = '/index/detail?good_id=' . GenerateEncrypt::encrypt($good['id'], ID_SIMPLE_KEY);
                            $good['photo'] = $photo;
                            unset($good['content']);
                        }
                    }
                    unset($good);
                    //$goodRes['view_more'] = '/index/more?district=' . GenerateEncrypt::encrypt($district_id, ID_SIMPLE_KEY);
                    $goodRes['view_more'] = '#';
                    $response[$district_id] = $goodRes;
                }
            }
            $redis->set($cacheKey,json_encode($response), 3600);
        }

        $this->assign('response', $response);

        $districtData = array_merge($this->getHaiDian(), $this->getChangPing(), $this->getShiJingShan(), $this->getMengTouGou());

        $this->assign('districts', $districtData);
        $this->getView()->display('second_view/index.phtml');
    }

    protected function getHaiDian(){
      $array = array(
          '010102' => array(
              'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
              'pic' =>'/static/second_asset/img/haidian.jpg',
              "id"=>"010102",
              'map_img' => 'http://api.map.baidu.com/staticimage?center=116.13952,40.10791&width=360&height=214&zoom=11',
              "name"=>"海淀",
          ),
      );
      return $array;
    }

    protected function getChangPing(){
      $array = array(
          '010107' => array(
              'des' => '昌平是首都北京的北大门，首都北京的卫星城。素有北京的后花园之称。位于北京西北部，被誉为“密尔王室，股胧重地”，素有“京师之枕”美称。古有居庸关、龙虎台等险隘以及明代陵寝。',
              'pic' =>'/static/second_asset/img/changping.jpg',
              "id"=> "010107",
              'map_img' => 'http://api.map.baidu.com/staticimage?center=116.201036,40.260705&width=360&height=214&zoom=11',
              "name"=> "昌平",
          ),
      );

      return $array;
    }

    protected function getTongZhou(){
      $array = array(
        'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。区内有各类文物点700余处，其中国家级文物保护单位10处，市级文物保护单位25处。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
        'pic' =>'',
        "name"=> "通州",
          //TODO id
        "weatherCode"=> "101010600",
        );
      return $array;
    }

    protected function getShiJingShan(){
      $array = array(
          '010110' => array(
              'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。区内有各类文物点700余处，其中国家级文物保护单位10处，市级文物保护单位25处。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
              'pic' =>'',
              "id"=> "010110",
              "name"=> "石景山",
          ),
      );

      return $array;
    }

    protected function getMengTouGou(){
      $array = array(
          '010114' => array(
              'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。区内有各类文物点700余处，其中国家级文物保护单位10处，市级文物保护单位25处。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
              'pic' =>'',
              "id"=> "010114",
              "name"=> "门头沟",
          )
      );
      return $array;
    }
}