<?php
namespace ZaiClient\Tests\Requests\Events;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Events\AddProductDetailViewEvent;

class AddProductDetailViewEventTest extends TestCase
{
    public function shouldSucceedDataWithoutRequestOptions()
    {
        return [
            [
                "user_id" => "test_user_id",
                "item_id" => "test_item_id",
            ],
        ];
    }

    public function shouldFailDataBatchWithoutRequestOptions()
    {
        return [
            [
                // case 1: user_id is given as list
                "user_id" => ["test_user_id_1", "test_user_id_2"],
                "item_id" => "test_item_id",
            ],
            [
                // case 2: item_id is given as list
                "user_id" => "test_user_id",
                "item_id" => ["test_item_id_1", "test_item_id_2"],
            ],
        ];
    }

    /**
     * @dataProvider shouldSucceedDataWithoutRequestOptions
     */
    public function testConstructorSucceedsWithoutOptions(
        $user_id,
        $item_id
    ) {
        $expect = [
            "user_id" => "test_user_id",
            "item_id" => "test_item_id",
            "timestamp" => microtime(true),
            "event_type" => "product_detail_view",
            "event_value" => "null",
            "from" => null,
            "is_zai_recommendation" => false,
            "time_to_live" => null,
        ];

        $request = new AddProductDetailViewEvent(
            $user_id,
            $item_id
        );

        $actual = $request->getPayload()->jsonSerialize();

        $expect_timestamp = $expect["timestamp"];
        $actual_timestamp = $actual["timestamp"];

        unset($expect["timestamp"]);
        unset($actual["timestamp"]);

        $this->assertEquals(
            $expect_timestamp,
            $actual_timestamp,
            "Timestamp should match within 5 milisecond",
            5 // Delta
        );
        $this->assertJsonStringEqualsJsonString(
            json_encode($expect),
            json_encode($actual)
        );
    }

    /**
     * @dataProvider shouldFailDataBatchWithoutRequestOptions
     */
    public function testConstructorFailsWithoutOptions(
        $user_id,
        $item_id
    ) {
        $this->expectException(\InvalidArgumentException::class);

        $expect = [
            "user_id" => "test_user_id",
            "item_id" => "test_item_id",
            "timestamp" => microtime(true),
            "event_type" => "product_detail_view",
            "event_value" => "null",
            "from" => null,
            "is_zai_recommendation" => false,
            "time_to_live" => null,
        ];

        $request = new AddProductDetailViewEvent(
            $user_id,
            $item_id
        );
    }
}
