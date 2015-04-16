<?php
/**
 * @name Bootstrap
 * @author rick
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initConfig() {
        //把配置保存起来
        $arrConfig = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('config', $arrConfig);

        // redis 配置文件
        $redis_ini  = new Yaf_Config_Ini(APPLICATION_PATH . '/conf/redis.ini');
        $redis_conf = $redis_ini->redis;
        Yaf_Registry::set('redis', $redis_conf);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        //注册一个插件
        $objSamplePlugin = new SamplePlugin();
        $dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        //在这里注册自己的路由协议,默认使用简单路由
    }

    public function _initView(Yaf_Dispatcher $dispatcher){
        //在这里注册自己的view控制器，例如smarty,firekylin
    }

    // 加载公共常量
    public function _initConstant(Yaf_Dispatcher $dispatcher){
        HelperConfig::init();
        Yaf_loader::import(APPLICATION_PATH . '/conf/constant.php');
    }

    // 加入公共的基类 action
    public function _initBaseAction(Yaf_Dispatcher $dispatcher)
    {
        Yaf_loader::import(APPLICATION_PATH . '/application/actions/BaseAction.php');
    }

    // 加入第三方 rbac,基于角色的权限管理
    //  @see: http://phprbac.net/index.php
    //  需要 yaf 打开 yaf.use_spl_autoload = 1
    public function _initRbac(Yaf_Dispatcher $dispatcher)
    {
        Yaf_loader::import(APPLICATION_PATH . '/contrib/PhpRbac/autoload.php');
    }

    /**
     * 加载 service 层
     * */
    public function _initService(Yaf_Dispatcher $dispatcher)
    {
        Yaf_loader::import(APPLICATION_PATH . '/application/service/autoload.php');
    }
}

