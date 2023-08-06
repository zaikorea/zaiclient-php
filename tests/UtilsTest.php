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
            ],
            [
                [
                    "test_option_1" => "test_value_1",
                    "test_option_2" => "test_value_2",
                ]
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
            [
                [
                    "not associated array element",
                    "not associated array element"
                ]
            ]
        ];

    }

    /**
     * @dataProvider shouldSucceed
     */
    public function testisAssociativeArraySucceed(
        $options
    ) {

        $this->assertTrue(Util::isAssociativeArray($options));
    }

    /**
     * @dataProvider shouldFail
     */
    public function testisAssociativeArrayFail(
        $options
    ) {

        $this->assertFalse(Util::isAssociativeArray($options));
    }
}
