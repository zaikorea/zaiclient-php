<?php
namespace Tests\Requests\Events;


use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Events\AddRateEvent;

class AddRateEventTest extends TestCase
{
    public function shouldSucceed()
    {
        return [
            "case 1" => [
                // case 1:
                "input" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "rating" => 5,
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "rate",
                    "event_value" => "5",
                    "from" => null,
                    "is_zai_recommendation" => false,
                    "time_to_live" => null,
                ]
            ],
            "case 2" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "rating" => 1.3,
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "rate",
                    "event_value" => "1.3",
                    "from" => null,
                    "is_zai_recommendation" => false,
                    "time_to_live" => null,
                ]
            ],
            "case 3" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "rating" => 0,
                    "request_options" => [
                        "is_zai_rec" => true,
                    ]
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "rate",
                    "event_value" => "0",
                    "from" => null,
                    "is_zai_recommendation" => true,
                    "time_to_live" => null,
                ]
            ]
        ];
    }

    public function shouldFail()
    {
        return [
            "case 1" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "rating" => "This is not a number",
                ],
                "expected" => null
            ]
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
        $rating = $input["rating"];

        if (array_key_exists("request_options", $input)) {
            $request = new AddRateEvent(
                $user_id,
                $item_id,
                $rating,
                $input["request_options"]
            );
        } else {
            $request = new AddRateEvent(
                $user_id,
                $item_id,
                $rating
            );
        }

        $actual = $request->getPayload()->jsonSerialize();

        $actual_timestamp = $actual["timestamp"];
        $expected_timestamp = $expected["timestamp"];

        $this->assertEquals(
            $expected_timestamp,
            $actual_timestamp,
            "Timestamp should match within 5 milisecond",
            5
        );

        unset($actual["timestamp"]);
        unset($expected["timestamp"]);

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            json_encode($actual)
        );
    }

    /**
     * @dataProvider shouldFail
     */
    public function testConstructorFail(
        $input,
        $expected
    ) {
        $this->expectException(\InvalidArgumentException::class);

        $user_id = $input["user_id"];
        $item_id = $input["item_id"];
        $rating = $input["rating"];

        if (array_key_exists("request_options", $input)) {
            $request = new AddRateEvent(
                $user_id,
                $item_id,
                $rating,
                $input["request_options"]
            );
        } else {
            $request = new AddRateEvent(
                $user_id,
                $item_id,
                $rating
            );
        }
    }

}
