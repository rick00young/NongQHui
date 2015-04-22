<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/15
 * Time: 下午3:30
 */

class aboutAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $this->getView()->display('second_view/about.phtml');
    }
}