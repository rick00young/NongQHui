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
        $this->getView()->display('second_view/index.phtml');
    }
}