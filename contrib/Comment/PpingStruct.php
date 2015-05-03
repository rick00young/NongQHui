<?php

class PpingStruct
{
    public static function getComment($content)
    {
        $commentStruct = array();
        $structs = array('fuid', 'tuid', 'parent', 'ancestor', 'content', 'score', 'subcount','status');

        foreach ($structs as $struct) {
            if ( !empty($content[$struct])) {
                $commentStruct[$struct] = $content[$struct];
            }
        }
        return $commentStruct;
    }

    public static function validateComment($comment)
    {
        $requireds = array('_id', 'oid', 'fuid', 'content', 'in_time');
        foreach ($requireds as $required) {
            if (! isset($comment[$required])) {
                return false;
            }
        }
        return true;
    }
}
