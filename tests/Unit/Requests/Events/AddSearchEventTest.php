<?php
namespace ZaiClient\Tests\Requests\Events;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Events\AddSearchEvent;
use ZaiClient\Tests\TestUtils;

class AddSearchEventTest extends TestCase
{
    public function shouldSucceed()
    {
        $random_query = TestUtils::generateRandomString(502);

        return [
            "case 1" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "search_query" => "test_search_query",
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "null",
                    "timestamp" => microtime(true),
                    "event_type" => "search",
                    "event_value" => "test_search_query",
                    "from" => null,
                    "is_zai_recommendation" => false,
                    "time_to_live" => null,
                ]
            ],
            "case 2" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "search_query" => $random_query,
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "null",
                    "timestamp" => microtime(true),
                    "event_type" => "search",
                    "event_value" => substr($random_query, 0, 500),
                    "from" => null,
                    "is_zai_recommendation" => false,
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
                    "search_query" => ["test_search_query_1", "test_search_query_2"],
                ],
                "expected" => []
            ],
            "case 2" => [
                "input" => [
                    "user_id" => "test_user_id",
                    "search_query" => 123,
                ],
                "expected" => []
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
        $search_query = $input["search_query"];

        if (array_key_exists("request_options", $input)) {
            $request = new AddSearchEvent(
                $user_id,
                $search_query,
                $input["request_options"]
            );
        } else {
            $request = new AddSearchEvent(
                $user_id,
                $search_query
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

    /**
     * @dataProvider shouldFail
     */
    public function testConstructorFail(
        $input,
        $expected
    ) {
        $this->expectException(InvalidArgumentException::class);

        $user_id = $input["user_id"];
        $search_query = $input["search_query"];

        if (array_key_exists("request_options", $input)) {
            $request = new AddSearchEvent(
                $user_id,
                $search_query,
                $input["request_options"]
            );
        } else {
            $request = new AddSearchEvent(
                $user_id,
                $search_query
            );
        }
    }
}
