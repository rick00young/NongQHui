<?php
class DbMongo
{
    private static $_mongo = null;

    private static function _getConnect()
    {
        $config = Yaf_Registry::get('config')->mongo->vips_web->master->toArray();
        $connectStr = self::_getConnectStr($config);

        try{
            $mongo = new MongoClient($connectStr);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $mongo;
    }

    //mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db
    public static function _getConnectStr($config)
    {
        if (empty($config['username']) || empty($config['password'])) {
            return "mongodb://{$config['hostname']}:{$config['port']}/pping";
        }

        return "mongodb://{$config['username']}:{$config['password']}@{$config['host']}:{$config['port']}/pping";
    }


    /**
     * @return mongo
     */
    public static function getInstance()
    {
        if(null == self::$_mongo){
            self::$_mongo = self::_getConnect();
        }
        return self::$_mongo;
    }
    
}
