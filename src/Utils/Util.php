<?php
namespace ZaiClient\Utils;

class Util
{

    /**
     * Check if array is associative
     *
     * @param array $arr
     * @return boolean
     */
    static function isAssoc(array $arr)
    {
        if (array() === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
