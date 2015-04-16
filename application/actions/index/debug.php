<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class debugAction extends Yaf_Action_Abstract
{
    public function execute($arg = null)
    {
        //echo REGISTER_MODEL_BUSINESS;

        // $conf = Yaf_Registry::get('config')->cookie->toArray();
        // var_export($conf);

        // $conf = Yaf_Registry::get('config')->cookie->domain;
        // var_export($conf);

        //redirect('http://www.baidu.com');
        //$db = DB::getOne('select * from shop');
        //var_export($db);

        //$rbac = new \PhpRbac\Rbac();
        //$all_roles =  $rbac->Users->allRoles(1);
        //print_r($all_roles);

        //Html::autoVersion('/static/admin/assets/js/ace.min.js');

        //$user_info = UserModel::getUserInfoByUid(1);
        //print_r($user_info);

        //UserModel::deleteUserByUid(2);

        //header("X-Accel-Redirect: /test/a.png");


        // 二维码数据
        $data = 'http://www.agrovips.com';

        // 生成的文件名
        $filename = false;

        // 纠错级别：L、M、Q、H
        $errorCorrectionLevel = 'L';

        // 点的大小：1到10
        $matrixPointSize = 4;
        QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    }
}
