<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class indexAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $this->getView()->display('admin/index.phtml');
    }
}
