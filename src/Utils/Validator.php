<?php
namespace ZaiClient\Utils;

use \InvalidArgumentException;

class Validator
{
    static function validateString($value, $min, $max, $nullable = false)
    {
        if ($nullable && is_null($value)) {
            return null;
        }

        $length = strlen($value);

        if ($length < $min || $length > $max) {
            throw new InvalidArgumentException("Value must be between $min and $max characters.");
        }

        return $value;
    }

    static function validateTimestamp($value, $nullable = false)
    {
        if ($nullable && is_null($value)) {
            return null;
        }

        $timestamp = strval(microtime(true));
        if (preg_match("/^\d{10}\.\d+$/", $timestamp)) { // e.g. 1690737483.076
            return $value;
        } else {
            throw new InvalidArgumentException("Value must be a valid microseconds unix timestamp string.");
        }
    }

    static function validateBoolean($value, $nullable = false)
    {
        if ($nullable && is_null($value)) {
            return null;
        }

        if (!is_bool($value)) {
            throw new InvalidArgumentException("Value must be a boolean.");
        }

        return $value;
    }

    static function validateFloat($value, $min, $max, $nullable = false)
    {
        if ($nullable && is_null($value)) {
            return null;
        }

        if (!is_float($value) || ($min !== null && $value < $min) || ($max !== null && $value > $max)) {
            throw new InvalidArgumentException("Value must be a float between $min and $max.");
        }

        return $value;
    }

    static function validateInt($value, $min, $max, $nullable = false)
    {
        if ($nullable && is_null($value)) {
            return null;
        }

        if (!is_int($value) || ($min !== null && $value < $min) || ($max !== null && $value > $max)) {
            throw new InvalidArgumentException("Value must be an integer between $min and $max.");
        }

        return $value;
    }
}
