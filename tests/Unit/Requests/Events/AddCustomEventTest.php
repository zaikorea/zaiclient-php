<?php
namespace ZaiClient\Tests\Requests\Events;

use PHPUnit\Framework\TestCase;
use ZaiClient\Requests\Events\AddCustomEvent;
use ZaiClient\Utils\Util;

class AddCustomEventTest extends TestCase
{
    public function shouldSucceed()
    {
        return [
            "case 1" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "event_type" => "custom_event_type",
                    "custom_event" => [
                        "item_id" => "test_item_id",
                        "value" => null,
                    ],
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "custom_event_type",
                    "event_value" => null, // This should change to "null" after collector-api update (2023/08/10)
                    "from" => null, // This should change too
                    "is_zai_recommendation" => false,
                    "time_to_live" => null,
                ]
            ],
            "case 2" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "event_type" => "test_event_type",
                    "custom_event" => [
                        ["item_id" => "test_item_id", "value" => null],
                        ["item_id" => "test_item_id", "value" => "watch"],
                    ],
                ],
                "expected" => [
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id",
                        "timestamp" => microtime(true),
                        "event_type" => "test_event_type",
                        "event_value" => null,
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ],
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id",
                        "timestamp" => microtime(true),
                        "event_type" => "test_event_type",
                        "event_value" => "watch",
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ]
                ]
            ],
            "case 3" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "event_type" => "custom_event_type",
                    "custom_event" => [
                        "item_id" => "test_item_id",
                        "value" => null,
                        "is_zai_rec" => true,
                        "from" => "test_from",
                    ],
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "custom_event_type",
                    "event_value" => null,
                    "from" => null,
                    "is_zai_recommendation" => true,
                    "time_to_live" => null,
                ]
            ],
        ];
    }

    /**
     * @dataProvider shouldSucceed
     */
    public function testConstructorSucceeds(
        $input,
        $expected
    ) {
        $user_id = $input["user_id"];
        $event_type = $input["event_type"];
        $custom_event = $input["custom_event"];

        if (array_key_exists("request_options", $input)) {
            $request = new AddCustomEvent(
                $user_id,
                $event_type,
                $custom_event,
                $input["request_options"]
            );
        } else {
            $request = new AddCustomEvent(
                $user_id,
                $event_type,
                $custom_event
            );
        }

        $actual = $request->getPayload();

        if (Util::isAssociativeArray($expected)) {
            $expected = [$expected];
            $actual = [$actual];
        }

        for ($i = 0; $i < count($expected); $i++) {
            $a = $actual[$i]->jsonSerialize();
            $e = $expected[$i];

            $expected_timestamp = $e["timestamp"];
            $actual_timestamp = $a["timestamp"];

            $this->assertEquals(
                $expected_timestamp,
                $actual_timestamp,
                "Timestamp should match within 5 milisecond",
                5 // Delta
            );

            unset($e["timestamp"]);
            unset($a["timestamp"]);

            $this->assertJsonStringEqualsJsonString(
                json_encode($e),
                json_encode($a)
            );
        }
    }
}
