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
        for ($i = 2; $i <= $count; $i++) {
            $pid = $i;
            $title = $rbac->Permissions->getTitle($pid);
            $description = $rbac->Permissions->getDescription($pid);

            $permissions[$pid] = array(
                'pid' => $pid,
                'title' => $title,
                'description' => $description,
            );
        }

        return $permissions;
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

