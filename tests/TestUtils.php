<?php
namespace ZaiClient\Tests;

use Ramsey\Uuid\Uuid;

class TestUtils
{
    static function generateRandomString($length = 101)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen($characters);
        $random_string = '';
        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[rand(0, $characters_length - 1)];
        }
        return $random_string;
    }

    static function generateUuid()
    {
        $uuid = Uuid::uuid4();

        return $uuid->toString();
    }

    // TODO: You might need to filter null values

}
