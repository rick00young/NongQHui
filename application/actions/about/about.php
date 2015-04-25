<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class aboutAction extends BaseAction
{
    public function run($arg = null)
    {
        $this->assign('_current_nav_', 'about');
        $this->getView()->display('second_view/about.phtml');
    }
}