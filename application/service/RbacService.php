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
}
/* vi:set ts=4 sw=4 et fdm=marker: */

