<?php
/**
 * redis 当作持久存储使用
 * */
class RedisDB
{
    private static $_redis = null;

    private function __construct()
    {
    }

    private static function _getConnect()
    {
        $config = Yaf_Registry::get('redis')->db->toArray();

        $redis = new Redis;

        for ($i = 0; $i < 3; $i++) {
            $ok = $redis->connect($config['hostname'], $config['port']);
            if ($ok) {
                break;
            }
        }

        if (! $ok) {
            throw new ExceptionServererror("Can't connect Redis ({$config['hostname']}:{$config['port']}).");
        }

        if (isset($config['password']) && $config['password']) {
            $redis->auth($config['password']);
        }

        return $redis;
    }
    /**
     * @return redis
     */
    public static function getInstance()
    {
        if (null == self::$_redis) {
            self::$_redis = self::_getConnect();
        }

        return self::$_redis;
    }

}

