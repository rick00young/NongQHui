<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class indexAction extends AdminBaseAction
{
    public function run($arg = null)
    {
        $uid = $this->getUid();
        if(!$uid){
            $this->display404();
        }

        $orderRes = OrderModel::getOrderByProducerUid($uid, 0, 1, 0);
        $count = isset($orderRes['count']) ? $orderRes['count'] : 0;
        $this->assign('count', $count);
        $this->assign('more_info_url','/admin/my_orders');
        $this->getView()->display('admin/index.phtml');
    }
}
