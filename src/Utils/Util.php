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
    static function isAssociativeArray(array $arr)
    {
        if (array() === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    static function isSequentialArray($array)
    {
        if (!is_array($array)) {
            return false;
        }

        if (count($array) == 0) {
            return true;
        }

        return array_keys($array) === range(0, count($array) - 1);
    }

}
