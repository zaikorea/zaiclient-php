<?php
namespace ZaiClient\Tests\Requests\Events;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Events\AddCartaddEvent;
use ZaiClient\Tests\TestUtils;

class AddCartaddEventTest extends TestCase
{
    public function shouldSucceed()
    {
        return [
            "case 1" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "cartadd",
                    "event_value" => "null",
                    "from" => null,
                    "is_zai_recommendation" => false,
                    "time_to_live" => null,
                ]
            ],
            "case 2" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "request_options" => [
                        "is_zai_rec" => true,
                    ]
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "cartadd",
                    "event_value" => "null",
                    "from" => null,
                    "is_zai_recommendation" => true,
                    "time_to_live" => null,
                ]

            ],
        ];
    }

    public function shouldFail()
    {
        return [
            "case 1" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "item_id" => TestUtils::generateRandomString(0),
                ],
                "expected" => [],
            ],
            "case 2" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "item_id" => TestUtils::generateRandomString(501),
                ],
                "expected" => [],
            ],
            "case 3" => [
                "input" => [
                    "user_id" => TestUtils::generateRandomString(0),
                    "item_id" => "test_item_id",
                ],
                "expected" => [],
            ],
            "case 4" => [
                "input" => [
                    "user_id" => TestUtils::generateRandomString(501),
                    "item_id" => "test_item_id",
                ],
                "expected" => [],
            ],
        ];
    }

    /**
     * @dataProvider shouldSucceed
     */
    public function testConstructorSucceed(
        $input,
        $expected
    ) {
        $user_id = $input["user_id"];
        $item_id = $input["item_id"];

        if (array_key_exists("request_options", $input)) {
            $request = new AddCartaddEvent(
                $user_id,
                $item_id,
                $input["request_options"]
            );
        } else {
            $request = new AddCartaddEvent(
                $user_id,
                $item_id
            );
        }

        $actual = $request->getPayload()->jsonSerialize();

        $actual_timestamp = $actual["timestamp"];
        $expected_timestamp = $expected["timestamp"];

        $this->assertEquals(
            $expected_timestamp,
            $actual_timestamp,
            "Timestamp should match within 5 milisecond",
            5 // Delta
        );

        unset($actual["timestamp"]);
        unset($expected["timestamp"]);

        $this->assertEquals(
            $expected,
            $actual,
            "Payload should match"
        );
    }

    /**
     * @dataProvider shouldFail
     */
    public function testConstructorFail(
        $input,
        $expected
    ) {
        $this->expectException(InvalidArgumentException::class);

        $user_id = $input["user_id"];
        $item_id = $input["item_id"];

        if (array_key_exists("request_options", $input)) {
            $request = new AddCartaddEvent(
                $user_id,
                $item_id,
                $input["request_options"]
            );
        } else {
            $request = new AddCartaddEvent(
                $user_id,
                $item_id
            );
        }
    }
}
