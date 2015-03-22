<?php
/**
 * @describe:
 * @author: rick
 * */
class Util
{
    public static function  isBinary($str)
    {
        $blk = substr($str, 0, 512);
        return (substr_count($blk, "\x00") > 0);
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */