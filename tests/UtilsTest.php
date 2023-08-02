<?php
namespace ZaiClient\Tests;

use PHPUnit\Framework\TestCase;

use RuntimeException;
use ZaiClient\Utils\Util;

class UtilsTest extends TestCase
{
    /**
     * @dataProvider shouldSucceed
     */
    public function shouldSucceed()
    {
        return [
            [
                [
                    "model" => "test_model",
                    "a" => "b",
                ],
            ]
        ];
    }

    /**
     * @dataProvider shouldFail
     */
    public function shouldFail()
    {
        return [
            [
                [1, 2, 3],
            ],
            [
                ["str_1", "str_2", "str_3"],
            ],
        ];

    }

    /**
     * @dataProvider shouldSucceed
     */
    public function testIsAssocSucceed(
        $options
    ) {

        $this->assertTrue(Util::isAssoc($options));
    }

    /**
     * @dataProvider shouldFail
     */
    public function testIsAssocFail(
        $options
    ) {

        $this->assertFalse(Util::isAssoc($options));
    }
}
