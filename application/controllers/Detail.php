<?php
/**
 * @name IndexController
 * @author rick
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class DetailController extends Yaf_Controller_Abstract {

    /**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/vips_web/index/index/index/name/rick 的时候, 你就会发现不同
     */


    public $action = array(
        'index' => 'actions/detail/index.php',

    );


    public function indexAction($name = "Stranger") {
        Yaf_Dispatcher::getInstance()->autoRender(false);
        $goodId = intval(GenerateEncrypt::decrypt($_GET['good_id'], ID_SIMPLE_KEY));
        $this->getView()->display('second_view/detail.phtml');
    }
}

