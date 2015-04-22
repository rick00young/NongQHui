<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/4/21
 * Time: 下午10:31
 */

class order_listAction extends BaseAction
{
    public function run($arg = null)
    {
        $this->getView()->display('second_view/order_list.phtml');
    }
}