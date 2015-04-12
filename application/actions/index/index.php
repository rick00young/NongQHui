<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class indexAction extends BaseAction
{
    public function run($arg = null)
    {
        Yaf_Dispatcher::getInstance()->autoRender(false);

       	$beiJing = array(
                  "id"=>"010101",
                  "name"=>"北京",
            );
       	$haiDian = array(
       		      "id"=>"010102",
                  "name"=>"海淀",
       		);

        $city_id = '0101';//北京
        $district_id = array('010102');

        //$redis = RedisCache::getInstance();

        //$redis->set('test','heiloe', 3600);
        //var_export($redis->get('test'));

        $this->getView()->display('second_view/index.phtml');
    }

    protected function getHaiDian(){
      $array = array(
        'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。区内有各类文物点700余处，其中国家级文物保护单位10处，市级文物保护单位25处。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
        'pic' =>'',
        "id"=>"010102",
        "name"=>"海淀",
        );
      return $array;
    }

    protected function getChangPing(){
      $array = array(
        'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。区内有各类文物点700余处，其中国家级文物保护单位10处，市级文物保护单位25处。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
        'pic' =>'',
        "id"=> "010107",
        "name"=> "昌平",
        );
      return $array;
    }

    protected function getTongZhou(){
      $array = array(
        'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。区内有各类文物点700余处，其中国家级文物保护单位10处，市级文物保护单位25处。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
        'pic' =>'',
        "name"=> "通州",
        "weatherCode"=> "101010600",
        );
      return $array;
    }

    protected function getShiJingShan(){
      $array = array(
        'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。区内有各类文物点700余处，其中国家级文物保护单位10处，市级文物保护单位25处。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
        'pic' =>'',
        "id"=> "010110",
        "name"=> "石景山",
        );
      return $array;
    }

    protected function getMenTouGou(){
      $array = array(
        'des' => '海淀区地处北京的上风、上水，是著名的风景旅游区。区内名胜古迹众多，园林风光宜人，旅游资源丰富，人居环境良好。区内有各类文物点700余处，其中国家级文物保护单位10处，市级文物保护单位25处。西山山秀林密，古木参天。凤凰岭、阳台山、鹫峰、百望山并列其间；南沙河、京密引水渠、昆明湖、玉渊潭等水域点缀其中。海淀区还开发建设了阳台山、凤凰岭自然风景区和翠湖水乡风景区。',
        'pic' =>'',
        "id"=> "010114",
        "name"=> "门头沟",
        );
      return $array;
    }
}