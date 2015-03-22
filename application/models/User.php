<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class UserModel
{
    const TABLE_NAME = 'user';

    public static function getUserInfoByUid($uid)
    {
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `id` = "%s" ', DB::escape($uid));
        $sql .= 'LIMIT 1';
        //echo $sql . PHP_EOL;exit;

        return DB::getOne($sql);
    }

    public static function getUserInfoByEmail($email)
    {
        $sql  = sprintf('SELECT * FROM `%s` ', self::TABLE_NAME);
        $sql .= sprintf('WHERE `email` = "%s" ', DB::escape($email));
        $sql .= 'LIMIT 1';
        //echo $sql . PHP_EOL;exit;

        return DB::getOne($sql);
    }

    /** 同名方法不好定位代码,有意加上类名前缀 */
    public static function uCreateNewUser($save_data)
    {
        $check = self::getUserInfoByEmail($save_data['email']);
        if (is_array($check) && count($check))
        {
            return 0;
        }

        DB::insert($save_data, self::TABLE_NAME);
        return DB::lastInsertId();
    }

    public static function deleteUserByUid($uid)
    {
        $update_dt = array(
            'deleted' => 1, // TODO hard code
        );

        return DB::update($update_dt, self::TABLE_NAME, $uid);
    }

    public static function updateLastLoginTime($last_login_time, $uid)
    {
        $update_dt = array(
            'last_login_time' => $last_login_time,
        );

        DB::update($update_dt, self::TABLE_NAME, $uid);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */

