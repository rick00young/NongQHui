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

    public static function bt($file, $line, $e)
    {
        SeasLog::debug(sprintf('[BT-BEGIN] %s: %s {{{', $file, $line));
        SeasLog::debug((string)$e);
        SeasLog::debug(sprintf('[BT-END] %s: %s }}}', $file, $line));
    }
}
/* vi:set ts=4 sw=4 et fdm=marker: */
