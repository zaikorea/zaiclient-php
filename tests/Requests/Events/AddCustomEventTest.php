<?php
namespace ZaiClient\Tests\Requests\Events;

use InvalidArgumentException;

use ZaiClient\Requests\Events\EventRequest;

class AddCustomEventTest extends EventRequest
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
                    "time_to_live" => 1000,
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "event_type" => "test_event_type",
                    "event_value" => "null",
                    "from" => "null",
                    "is_zai_rec" => false,
                    "time_to_live" => 1000,
                ]
            ],
            "case 2" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "event_type" => "custom_event_type",
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
                        "event_value" => "null",
                        "from" => "null",
                        "is_zai_rec" => false,
                        "time_to_live" => null,
                    ],
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id",
                        "timestamp" => microtime(true),
                        "event_type" => "test_event_type",
                        "event_value" => "watch",
                        "from" => "null",
                        "is_zai_rec" => false,
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
                    "time_to_live" => 1000,
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "test_event_type",
                    "event_value" => "null",
                    "from" => "test_from",
                    "is_zai_rec" => true,
                    "time_to_live" => 1000,
                ]
            ],
        ];
    }

    /**
     * @dataprovider shouldSucceed
     */
    public function testConstructorSucceeds(
        $input,
        $expected
    ) {
        $user_id = $input["user_id"];
        $custom_action = $input["custom_action"];

        if (array_key_exists("reqeust_options")) {
            $request = new AddCustomEvent(
                $user_id,
                $custom_action,
                $input["request_options"]
            );
        } else {
            $request = new AddCustomEvent(
                $user_id,
                $custom_action,
                $input["request_options"]
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
                "Timestamp should match within 0.1 microsecond",
                0.1 // Delta
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
