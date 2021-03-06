<?php
/**
 * @describe: 将 action 抽象出一个基类,把常用的方法进行简化.
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
abstract class AdminBaseAction extends Yaf_Action_Abstract
{
    abstract public function run($arg = null);

    protected $user_info = array(
        'uid'   => 0,
        'base_info' => array(),
    );

    protected $_page_title;
    protected $_page_keywords;
    protected $_page_description;
    protected $_current_nav = 'rbac';

    protected function beforeExecute()
    {
        // 满足特定条件的 controller 或 action 可以在此路由
        // 以及初始化一些全局属性.
        if (isset($_SESSION['user_info']) && count($_SESSION['user_info']) && $_SESSION['user_info']['uid'] > 0)
        {
            $this->user_info = $_SESSION['user_info'];
        }
        $this->assign('_uid_', $this->user_info['uid']);
        $this->assign('_current_nav', $this->_current_nav);
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

            /** rbac 权限控制 */
            $uid = $this->user_info['uid'];
            if (false !== strpos($action_name, 'rbac_'))
            {
                if (SUPERVISOR_UID == $uid)
                {
                    return true;
                }
                else
                {
                    Html::setFlash('没有授权');
                    header('Location: /admin');
                    return false;
                }
            }

            $pid = RbacService::getPidByTitle($action_name);
            if ($pid > 1) // 1: root
            {
                // 说明此 action 有权限控制
                if (RbacService::check($pid, $uid))
                {
                    return true;
                }
                else
                {
                    Html::setFlash('没有授权,请联系管理员');
                    $this->redirect('/admin');
                    return false;
                }
            }
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
    protected function setTDK(){
        $this->assign('_page_title', $this->_page_title);
        $this->assign('_page_keywords', $this->_page_keywords);
        $this->assign('_page_description', $this->_page_description);
    }

    protected function setCurrentNav(){
        $this->assign('_current_nav', $this->_current_nav);
    }

    protected function isMobilePlatform(){
        if(stristr($_SERVER['HTTP_USER_AGENT'],'Android')) {
            return true;
        }else if(stristr($_SERVER['HTTP_USER_AGENT'],'iPhone')){
            return true;
        }
        return false;
    }

    protected function getBeiJingJson(){
        $beijingJson = '{
            "id": "01",
            "name": "北京",
            "city": {
              "id": "0101",
              "name": "北京",
              "county": [
                {
                  "id": "010101",
                  "name": "北京",
                  "weatherCode": "101010100"
                },
                {
                  "id": "010102",
                  "name": "海淀",
                  "weatherCode": "101010200"
                },
                {
                  "id": "010103",
                  "name": "朝阳",
                  "weatherCode": "101010300"
                },
                {
                  "id": "010104",
                  "name": "顺义",
                  "weatherCode": "101010400"
                },
                {
                  "id": "010105",
                  "name": "怀柔",
                  "weatherCode": "101010500"
                },
                {
                  "id": "010106",
                  "name": "通州",
                  "weatherCode": "101010600"
                },
                {
                  "id": "010107",
                  "name": "昌平",
                  "weatherCode": "101010700"
                },
                {
                  "id": "010108",
                  "name": "延庆",
                  "weatherCode": "101010800"
                },
                {
                  "id": "010109",
                  "name": "丰台",
                  "weatherCode": "101010900"
                },
                {
                  "id": "010110",
                  "name": "石景山",
                  "weatherCode": "101011000"
                },
                {
                  "id": "010111",
                  "name": "大兴",
                  "weatherCode": "101011100"
                },
                {
                  "id": "010112",
                  "name": "房山",
                  "weatherCode": "101011200"
                },
                {
                  "id": "010113",
                  "name": "密云",
                  "weatherCode": "101011300"
                },
                {
                  "id": "010114",
                  "name": "门头沟",
                  "weatherCode": "101011400"
                },
                {
                  "id": "010115",
                  "name": "平谷",
                  "weatherCode": "101011500"
                }
              ]
            }
        }';
        return $beijingJson;

    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

