<?php
/**
 * @describe:
 * @author: Jerry Yang(hy0kle@gmail.com)
 * */
class DB
{
    private static $_db = null;
    private static function getDb()
    {
        if (null == self::$_db)
        {
            // 初始化数据库

            $conf = Yaf_Registry::get('config')->mysql->vips_web->master->toArray();
            $dsn  = "mysql:dbname={$conf['database']};host={$conf['hostname']};port={$conf['port']}";
            try {
                $db = new PDO($dsn, $conf['username'], $conf['password']);
                // 设置为异常模式
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // 忽略掉配置的字符,强制使用 utf8
                $db->query('SET NAMES UTF8');
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getCode();
                SeasLog::error('Connection failed: ' . $e->getCode() . ' ' . $e->getMessage());
                exit;
            }
            self::$_db = $db;
        }
        return self::$_db;
    }
    /**
     * @brief query 屏蔽掉 $db 句柄, 执行 sql 语句的标准入口
     *
     * @param: $sql
     *
     * @return:
     */
    public static function query($sql)
    {
        $db = self::getDb();

        try {
            return $db->query($sql, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo 'Something has wrong: ' . $e->getCode();
            $log = sprintf('[Error]: %s | [Message]: %s | [SQL]: %s', $e->getCode(), $e->getMessage(), $sql);
            SeasLog::error($log);
            Util::bt(__FILE__, __LINE__, $e);
            exit();
        }
    }
    /**
     * @brief select 以数组方式返回 select 的结果集
     *
     * @param: $sql
     *
     * @return: array
     */
    public static function select($sql)
    {
        return self::query($sql);
    }


    /**
     * get一行数据
     *
     * @param string $sql
     * @return array
     */
    public static function getOne($sql)
    {
        $res = self::select($sql);
        return $res->fetch();
    }

    /**
     * get多行数据
     *
     * @param string $sql
     * @return array
     */
    public static function getAll($sql)
    {
        $res = self::select($sql);
        return $res->fetchAll();
    }


    /**
     * 通用insert方法
     * @param unknown $save_data
     * @param unknown $table
     * @return Ambigous <:, string>
     */
    public static function insert($save_data, $table, $unEscape = array())
    {

        $set = array();

        foreach ($save_data as $field => $value)
        {
            if (!in_array($field, $unEscape)) {
                $value = DB::escape($value);
            }

            $set[] = "`{$field}` = '{$value}'";
        }
        $sql = sprintf('INSERT INTO `%s` SET %s', $table, implode(', ', $set));

        DB::query($sql);

        return DB::lastInsertId();
    }

    /**
     * 通用insertOrUpdate方法  未完成
     * @param unknown $save_data
     * @param unknown $table
     * @return Ambigous <:, string>
     */
    public static function insertOrUpdateOnDuplicateKey($save_data, $table, $unEscape = array())
    {

        $set = array();

        foreach ($save_data as $field => $value)
        {
            if (!in_array($field, $unEscape)) {
                $value = DB::escape($value);
            }

            $set[] = "`{$field}` = '{$value}'";
        }
        $sql = sprintf('INSERT INTO `%s` SET %s', $table, implode(', ', $set));
        $sql .= sprintf('');

        DB::query($sql);

        return DB::lastInsertId();
    }

    /**
     * 通用replaceInto方法  注意:此方法会有副作用,老数据会被删除,新的数据会被插入
     * @param unknown $save_data
     * @param unknown $table
     * @return Ambigous <:, string>
     */
    public static function replaceInto($save_data, $table, $unEscape = array())
    {

        $set = array();

        foreach ($save_data as $field => $value)
        {
            if (!in_array($field, $unEscape)) {
                $value = DB::escape($value);
            }

            $set[] = "`{$field}` = '{$value}'";
        }
        $sql = sprintf('REPLACE INTO `%s` SET %s', $table, implode(', ', $set));

        DB::query($sql);

        return DB::lastInsertId();
    }

    /**
     * 通用update方法
     * @param unknown $save_data
     * @param unknown $table
     * @return Ambigous <:, string>
     */
    public static function update($save_data, $table, $id, $unEscape = array())
    {
        $set = array();

        foreach ($save_data as $field => $value)
        {
            if(!in_array($field, $unEscape)){
                $value = DB::escape($value);
            }

            $set[] = "`{$field}` = '{$value}'";
        }
        $sql = sprintf('UPDATE `%s` SET %s WHERE id = %d', $table, implode(', ', $set), $id);
        return DB::query($sql);

    }

    /**
        * @brief lastInsertId 取最后插入的 id
        *
        * @return: int | bool
     */
    public static function lastInsertId()
    {
        $db = self::getDb();
        return $db->lastInsertId();
    }
    /**
        * @brief escape 防 sql 注入
        *
        * @param: $str
        *
        * @return: string
     */
    public static function escape($str)
    {
        return Util::isBinary($str) ? addslashes($str) : htmlspecialchars(trim($str), ENT_QUOTES);
    }

    /**
     * select 后的count方法
     */
    public static function foundRows()
    {
        $sql = "SELECT FOUND_ROWS()";
        $res =  DB::query($sql);
        $row =  $res->fetch();

        if (false === $row) {
            return false;
        } elseif (null === $row) {
            return null;
        }

        if (!is_array($row)) {
            return false;
        }

        $ret = array_values($row);
        return $ret[0];
    }

}
