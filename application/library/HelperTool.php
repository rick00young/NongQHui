<?php
/**
 * Created by PhpStorm.
 * User: rick
 * Date: 15/3/23
 * Time: 下午10:38
 */

class HelperTool{
    public static function strCut($str,$limit_length,$type=true) {
        //返回的字符串
        $return_str   = "";
        //总长度，一个汉字算两个位置
        $total_length = 0;
        // 以utf-8格式求字符串的长度，每个汉字算一个长度
        $len = mb_strlen($str,'utf8');
        for ($i = 0; $i < $len; $i++) {
            //以utf-8格式取得第$i个位置的字符，取的长度为1
            $curr_char   = mb_substr($str,$i,1,'utf8');
            //如果字符的ACII编码大于127，则此字符为汉字，算两个长度
            $curr_length = ord($curr_char) > 127 ? 2 : 1;
            // 计算下一个utf8单位字符的长度，结果存入next_length
            if ($i != $len -1) {
                $next_length = ord(mb_substr($str,$i+1,1,'utf8')) > 127 ? 2 : 1;
            } else {
                $next_length = 0;//如果到最后一个字符了，则结束
            }
            if ( $total_length + $curr_length + $next_length > $limit_length ) {
                if($type){
                    $return_str .= $curr_char;
                    return "{$return_str}...";
                }else{
                    $return_str .= $curr_char;
                    return "{$return_str}";
                }
            } else {
                $return_str .= $curr_char;
                $total_length += $curr_length;
            }
        }
        return $return_str;
    }
    //对象转数组
    public static function objToArr($obj){
        if(is_object($obj)) {
            $obj = (array)$obj;
            $obj = self::objToArr($obj);
        } elseif(is_array($obj)) {
            foreach($obj as $key => $value) {
                $obj[$key] = self::objToArr($value);
            }
        }
        return $obj;
    }

    //数组转对象
    public static function arrToObj($arr){
        if(is_array($arr)){
            $arr = (object) $arr;
            $arr = self::arrToObj($arr);
        }elseif(is_object($arr)){
            foreach($arr as $key=>$value){
                $arr->$key = self::arrToObj($value);
            }
        }
        return $arr;
    }

    /*
     * @param array $arr 数据源
     * @param string $key_field 按照什么键的值进行转换
     * @param string $value_field 对应的键值
     *
     * @return array 转换后的 HashMap 样式数组
     */
    public static function arrToHashmap($arr, $key_field, $value_field = null){
        $ret = array();
        if ($value_field)
        {
            foreach ($arr as $row)
            {
                $ret[$row[$key_field]] = $row[$value_field];
            }
        }
        else
        {
            foreach ($arr as $row)
            {
                $ret[$row[$key_field]] = $row;
            }
        }
        return $ret;
    }


    /**
     * 将一个二维数组按照指定字段的值分组
     *
     * 用法：
     * @code php
     * $rows = array(
     *     array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *     array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *     array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *     array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *     array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *     array('id' => 6, 'value' => '6-1', 'parent' => 3),
     * );
     * $values = Helper_Array::groupBy($rows, 'parent');
     *
     * dump($values);
     *   // 按照 parent 分组的输出结果为
     *   // array(
     *   //   1 => array(
     *   //        array('id' => 1, 'value' => '1-1', 'parent' => 1),
     *   //        array('id' => 2, 'value' => '2-1', 'parent' => 1),
     *   //        array('id' => 3, 'value' => '3-1', 'parent' => 1),
     *   //   ),
     *   //   2 => array(
     *   //        array('id' => 4, 'value' => '4-1', 'parent' => 2),
     *   //        array('id' => 5, 'value' => '5-1', 'parent' => 2),
     *   //   ),
     *   //   3 => array(
     *   //        array('id' => 6, 'value' => '6-1', 'parent' => 3),
     *   //   ),
     *   // )
     * @endcode
     *
     * @param array $arr 数据源
     * @param string $key_field 作为分组依据的键名
     *
     * @return array 分组后的结果
     */
    public static function arrGroupBy($arr, $key_field)
    {
        $ret = array();
        foreach ($arr as $row)
        {
            $key = $row[$key_field];
            $ret[$key][] = $row;
        }
        return $ret;
    }


    public static function arrayColumn($array, $column) {
        if (!is_array($array)) {
            return array();
        }
        if(function_exists('array_column')){
            return array_column($array, $column);
        }

        $ret = array();
        foreach ($array as $item) {
            if (isset($item[$column])) {
                $ret[] = $item[$column];
            }
        }
        return $ret;
    }

    public static  function nicetime($timestamp) {
        $duration = time() - $timestamp;

        $strEcho = date("Y-m-d H:i", $timestamp);

        if( $duration < 60 ){
            $strEcho = "刚刚";
        }elseif( $duration < 3600 ){
            $strEcho = intval($duration/60)."分钟前";
        }elseif( $duration >= 3600 && $duration <= 86400  ){
            $strEcho = intval($duration/3600)."小时前";
        }elseif( $duration > 86400 && $duration <= 172800 ){
            $strEcho = '昨天'.date("H:i", $timestamp);
        }elseif( $duration > 172800 && $duration <= 86400 * 30 ){
            $strEcho = floor($duration / 86400).'天前';
        }elseif( $duration > 86400 * 30 && $duration <= 86400 * 359 ){
            $strEcho = floor($duration /(86400 * 30 )).'月前';
        } elseif ( date("Y") === date("Y", $timestamp) ) {
            $strEcho = date("Y-m-d H:i", $timestamp);
        }

        return $strEcho;
    }

}