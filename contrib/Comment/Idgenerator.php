<?php
/**
 * id生成器
 * @param namespace
 * @param option
 *
 * @return
 */
class Idgenerator
{
    // 用于id生成器的mongo db name
    const MONGO_DB_NAME = '_seq';

    // 用于id生成器的mongo table name
    const MONGO_COLLECTION_NAME = 'id_generator';

    // 启用的block
    private static $_enableBlocks = array('good',);

    /**
     * 使用mongodb产生子增id
     * @param namespace
     * @param option
     *
     * @return
     */
    public static function incrementMongoId($namespace, array $option = array())
    {
        if (! self::getBlockId($namespace)) {
            throw new Exception("not allowed block [$namespace]", 400);
        }

        $option += array(
            'init' => 1,
            'step' => 1,
        );

        $mongo = DbMongo::getInstance();

        $dbName = self::MONGO_DB_NAME;
        $collectionName = self::MONGO_COLLECTION_NAME;

        $instance = $mongo->$dbName;

        $seq = $instance->command(array(
            'findAndModify' => $collectionName,
            'query'         => array('_id' => $namespace),
            'update'        => array('$inc' => array('id' => $option['step'])),
            'new'           => true,
        ));

        if (isset($seq['value']['id'])){
            return $seq['value']['id'];
        }

        $instance->$collectionName->insert(array(
            '_id' => $namespace,
            'id'  => $option['init'],
        ));

        return $option['init'];
    }

    /**
     * 获取启用的blockId
     * @param namespace
     *
     * @return
     */
    public static function getBlockId($namespace)
    {
        if (! in_array($namespace, self::$_enableBlocks)) {
            return false;
        }
        $mapping = array_flip(self::$_enableBlocks);

        return $mapping[$namespace] + 1;
    }

}
