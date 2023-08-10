<?php
namespace ZaiClient\Tests\Requests\Events;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Events\AddPageViewEvent;

class AddPageViewEventTest extends TestCase
{
    public function shouldSucceed()
    {
        return [
            "case 1" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "page_type" => "test_page_type",
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "null",
                    "timestamp" => microtime(true),
                    "event_type" => "page_view",
                    "event_value" => "test_page_type",
                    "from" => null,
                    "is_zai_recommendation" => false,
                    "time_to_live" => null,
                ]
            ],
        ];
    }

    public function shouldFail()
    {
        return [
            "case 1" => [],
            "case 2" => [],
            "case 3" => [],
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
        $page_type = $input["page_type"];

        if (array_key_exists("request_options", $input)) {
            $request = new AddPageViewEvent(
                $user_id,
                $page_type,
                $input["request_options"]
            );
        } else {
            $request = new AddPageViewEvent(
                $user_id,
                $page_type
            );
        }

        $actual = $request->getPayload()->jsonSerialize();

        $actual_timestamp = $actual["timestamp"];
        $expected_timestamp = $expected["timestamp"];

        $this->assertEquals(
            $expected_timestamp,
            $actual_timestamp,
            "Timestamp should match within 0.1 microsecond",
            0.1 // Delta
        );

        unset($actual["timestamp"]);
        unset($expected["timestamp"]);

        $this->assertEquals(
            $expected,
            $actual,
            "Payload should match"
        );
    }

    // public function testConstructorFail(
    //     $input,
    //     $expected
    // ) {

    // }

}
