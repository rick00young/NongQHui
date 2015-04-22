<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class singinAction extends AdminBaseAction
{
    public function run($arg = null)
    {
    	Yaf_Dispatcher::getInstance()->disableView();
        echo 'check';

        // TODO 校验成功跳转至管理首页
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

