<?php
namespace ZaiClient\Utils;

use \InvalidArgumentException;

class Validator
{
    const VARIABLE_REQUIREMENT_MESG = "[Set a var_name for better error messages]";
    const DEFAULT_OPTIONS = [
        "nullable" => false,
        "var_name" => Validator::VARIABLE_REQUIREMENT_MESG,
    ];

    static function validateString(
        $value,
        $min,
        $max,
        $options = array())
    {
        $_options = array_merge(Validator::DEFAULT_OPTIONS, $options);

        if ($_options["nullable"] && is_null($value)) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(var_export($value, true) . " must be a string.");
        }

        $length = strlen($value);

        if ($length < $min || $length > $max) {
            throw new InvalidArgumentException($_options["var_name"] . " must be between $min and $max characters long but $value");
        }

        return $value;
    }

    static function validateTimestamp(
        $value,
        $options = array())
    {
        $_options = array_merge(Validator::DEFAULT_OPTIONS, $options);

        if ($_options["nullable"] && is_null($value)) {
            return null;
        }

        if (!is_float($value) && !is_int($value)) {
            throw new InvalidArgumentException($_options["var_name"] . " must be a float not " . var_export($value, true));
        }

        if ($value > 1648871097. && $value < 2147483647.) { // e.g. 1690737483.076
            return $value;
        } else {
            throw new InvalidArgumentException($_options["var_name"] . " must be a valid microseconds unix timestamp string not " . var_export($value, true));
        }
    }

    static function validateBoolean(
        $value,
        $options = array())
    {
        $_options = array_merge(Validator::DEFAULT_OPTIONS, $options);

        if ($_options["nullable"] && is_null($value)) {
            return null;
        }

        if (!is_bool($value)) {
            throw new InvalidArgumentException($_options["var_name"] . " must be a boolean, not " . var_export($value, true));
        }

        return $value;
    }

    static function validateFloat(
        $value,
        $min,
        $max,
        $options = array())
    {
        $_options = array_merge(Validator::DEFAULT_OPTIONS, $options);

        if ($_options["nullable"] && is_null($value)) {
            return null;
        }

        if (!is_float($value) || ($min !== null && $value < $min) || ($max !== null && $value > $max)) {
            throw new InvalidArgumentException($_options["var_name"] . " must be a float between $min and $max not " . var_export($value, true));
        }

        return $value;
    }

    static function validateInt(
        $value,
        $min,
        $max,
        $options = array())
    {
        $_options = array_merge(Validator::DEFAULT_OPTIONS, $options);

        if ($_options["nullable"] && is_null($value)) {
            return null;
        }

        if (!is_int($value) || ($min !== null && $value < $min) || ($max !== null && $value > $max)) {
            throw new InvalidArgumentException($_options["var_name"] . " must be an integer between $min and $max not " . var_export($value, true));
        }

        return $value;
    }

    static function validateStringArrays(
        $value,
        $arrMin,
        $arrMax,
        $options = [
            "min" => 0,
            "max" => 500,
        ],
        $options = array())
    {
        $_options = array_merge(Validator::DEFAULT_OPTIONS, $options);

        if ($_options["nullable"] && is_null($value)) {
            return null;
        }

        if (count($value) < $arrMin && $arrMax < count($value)) {
            throw new InvalidArgumentException($_options["var_name"] . " array must contain between $arrMin and $arrMax elements not " . count($value) . "elements");
        }

        try {
            for ($i = 0; $i < count($value); $i++) {
                $value[$i] = Validator::validateString($value[$i], $lenConstr["min"], $lenConstr["max"], [
                    "var_name" => "elements in " . $_options["var_name"]
                ]);
            }
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException("Array must contain strings between {$options['min']} and {$options['max']} characters.");
        }

        return $value;
    }

    static function validateJsonSerializable(
        $value,
        $max,
        $options = [
            "nullable" => false,
            "var_name" => Validator::VARIABLE_REQUIREMENT_MESG
        ]
    ) {
        if ($options["nullable"] && is_null($value)) {
            return null;
        }

        if (!is_array($value) || !Util::isAssoc($value)) {
            throw new InvalidArgumentException("Recommendation options must be an associative array.");
        }

        if ($max < strlen(json_encode($value))) {
            throw new InvalidArgumentException("Recommendation options must be less than $max when converted to string.");
        }

        return $value;
    }
}
