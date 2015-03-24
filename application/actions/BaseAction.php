<?php
/**
 * @describe: 将 action 抽象出一个基类,把常用的方法进行简化.
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
abstract class BaseAction extends Yaf_Action_Abstract
{
    abstract public function run($arg = null);

    protected $user_info = array(
        'uid'   => 0,
        'base_info' => array(),
    );

    protected function beforeExecute()
    {
        // 满足特定条件的 controller 或 action 可以在此路由
        // 以及初始化一些全局属性.
        if (isset($_SESSION['user_info']) && count($_SESSION['user_info']) && $_SESSION['user_info']['uid'] > 0)
        {
            $this->user_info = $_SESSION['user_info'];
        }
        $this->assign('_uid_', $this->user_info['uid']);

        /**
         * 一些常用公共数据
         */
        $current_controller = strtolower($this->getRequest()->getControllerName());
        $action_name        = strtolower($this->getRequest()->getActionName());

        if ('admin' == $current_controller)
        {
            $unnecessary_login_actions = array(
                'login', 'signin',
                'register', 'logout',
                'create_user',
            );
            if (! in_array($action_name, $unnecessary_login_actions) && 0 == $this->user_info['uid'])
            {
                header('Location: /admin/login');
                return false;
            }

            // 认证后需要写 session, user_info.
        }

        return true;
    }

    protected function afterExecute()
    {
        return true;
    }

    /**
     * @param mixed $arg,...
     * @return mixed
     */
    public function execute($arg = null)
    {
        if ($this->beforeExecute())
        {
            if ($this->run($arg))
            {
                return $this->afterExecute();
            }
        }
        return $this->afterExecute();
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return string
     */
    public function getRequestParam($name, $default = '')
    {
        return isset($_REQUEST[$name]) ? DB::escape($_REQUEST[$name]) : $default;
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return string
     */
    public function get($name, $default = '')
    {
        return isset($_GET[$name]) ? DB::escape($_GET[$name]) : $default;
    }

    /**
     * @param $name
     * @param string $defaultValue
     * @return string
     */
    public function post($name, $default = '')
    {
        return isset($_POST[$name]) ? DB::escape($_POST[$name]) : $default;
    }

    public function post_unescape($name, $default = '')
    {
        return isset($_POST[$name]) ? $_POST[$name] : $default;
    }

    // 简化 assign 模板变量的操作
    public function assign($key, $value)
    {
        $this->getView()->assign($key, $value);
    }

    // 简写,要写太多的长代码.
    /*
    public function display($tpl, array $parameters = NULL)
    {
        $this->getView()->display($tpl);
    }
    */

    public function getUid(){
        return isset($this->user_info['uid']) ? $this->user_info['uid'] : 0;
    }

    protected function jsonReturn($info){
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode($info));
    }

    protected function display404(){
        $this->getView()->display('error/404.phtml');
        die;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

