<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class RbacService
{
    private static $_rbac = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (null === self::$_rbac)
        {
            self::$_rbac = new PhpRbac\Rbac();
        }

        return self::$_rbac;
    }

    /**
        * @brief getPidByTitle
        *
        * @param: $title 由调用方来防 SQL 注入
        *
        * @return: Returns the Entity's ID if successful, null if unsuccessful.
     */
    public static function getPidByTitle($title)
    {
        $rbac  = self::getInstance();
        return $rbac->Permissions->returnId($title);
    }

    public static function getAllPermissions()
    {
        $permissions = array();
        $rbac  = self::getInstance();
        $count = $rbac->Permissions->count();

        /** 1 是 root, 不可更改 */
        for ($i = 2; $i <= $count; $i++)
        {
            $pid = $i;

            $permissions[$pid] = self::getPermissionByPid($pid);
        }

        return $permissions;
    }

    public static function getPermissionByPid($pid)
    {
        $pid += 0;
        $rbac  = self::getInstance();
        $permission = false;

        $title = $rbac->Permissions->getTitle($pid);
        $description = $rbac->Permissions->getDescription($pid);

        if ($title && $description)
        {
            $permission = array(
                'pid' => $pid,
                'title' => $title,
                'description' => $description,
            );
        }

        return $permission;
    }

    public static function getAllRoles()
    {
        $roles = array();

        $rbac  = self::getInstance();
        $count = $rbac->Roles->count();

        /** 1 是 root, 不可更改 */
        for ($i = 2; $i <= $count; $i++)
        {
            $rid = $i;

            $roles[$rid] = self::getRoleByRid($rid);
        }

        return $roles;
    }

    public static function getRoleByRid($rid)
    {
        $rbac  = self::getInstance();
        $role  = false;
        $rid  += 0;

        $title = $rbac->Roles->getTitle($rid);
        $description = $rbac->Roles->getDescription($rid);

        if ($title && $description)
        {
            $role = array(
                'rid' => $rid,
                'title'       => $title,
                'description' => $description,
            );
        }

        return $role;
    }

    public static function getPermissions4RoleByRid($rid)
    {
        $rid += 0;
        $dt = array();
        $rbac = self::getInstance();

        $ret = $rbac->Roles->permissions($rid);
        if (null !== $ret)
        {
            $dt = $ret;
        }

        return $dt;
    }

    public static function getRoles4UserByUid($uid)
    {
        $uid += 0;
        $dt = array();

        $user_roles = self::getallRolesByUid($uid);
        foreach ($user_roles as $k => $v)
        {
            $dt[] = $v['ID'];
        }

        return $dt;
    }

    public static function checkRoleHasPermission($rid, $pid)
    {
        $rid += 0;
        $pid += 0;
        $rbac = self::getInstance();

        return $rbac->Roles->hasPermission($rid, $pid);
    }

    public static function checkUserHasRoles($rid, $uid)
    {
        $rid += 0;
        $uid += 0;
        $rbac = self::getInstance();

        return $rbac->Users->hasRole($rid, $uid);
    }

    /** 权限和角色的授权与回收 {{{ */
    public static function assign($rid, $pid)
    {
        $rid += 0;
        $pid += 0;
        $rbac = self::getInstance();

        return $rbac->Roles->assign($rid, $pid);
    }

    public static function unassign($rid, $pid)
    {
        $rid += 0;
        $pid += 0;
        $rbac = self::getInstance();

        return $rbac->Roles->unassign($rid, $pid);
    }
    /** }}} */

    /** Checks whether a user has a permission or not. */
    /**
     *  @return Returns true if a user has a permission, false if otherwise.
     */
    public static function check($pid, $uid)
    {
        $pid += 0;
        $uid += 0;
        $rbac = self::getInstance();

        return $rbac->check($pid, $uid);
    }

    public static function usersAssign($rid, $uid)
    {
        $rid += 0;
        $uid += 0;
        $rbac = self::getInstance();

        return $rbac->Users->assign($rid, $uid);
    }

    public static function usersUnassign($rid, $uid)
    {
        $rid += 0;
        $uid += 0;
        $rbac = self::getInstance();

        return $rbac->Users->unassign($rid, $uid);
    }

    public static function getallRolesByUid($uid)
    {
        $uid += 0;
        $rbac = self::getInstance();

        $dt = array();
        $ret= $rbac->Users->allRoles($uid);
        if (null !== $ret)
        {
            $dt = $ret;
        }

        return $dt;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

