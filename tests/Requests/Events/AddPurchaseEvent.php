<?php
namespace ZaiClient\Tests\Requests\Events;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Events\AddPurchaseEvent;

class AddPurchaseEventTest extends TestCase
{
    public function shouldSucceedDataWithoutRequestOptions()
    {
        return [
            [
                // case 1
                "user_id" => "test_user_id",
                "order" => [
                    "item_id" => "test_item_id",
                    "price" => 10000,
                    "count" => 3
                ],
            ],
            [
                // case 2
                "user_id" => "test_user_id",
                "orders" => [
                    [
                        "item_id" => "test_item_id",
                        "price" => 10000,
                        "count" => 3
                    ],
                    [
                        "item_id" => "test_item_id_2",
                        "price" => 30000,
                        "count" => 1
                    ]
                ]
            ]
        ];
    }

    public function shouldFailDataWithoutRequestOptions()
    {
        return [
            [
                // case 1: user_id is empty string
                "user_id" => "",
                "order" => [
                    "item_id" => "test_item_id",
                    "price" => 10000,
                    "count" => 3
                ],
            ],
            [
                // case 2 : user_id is null
                "user_id" => null,
                "orders" => [
                    [
                        "item_id" => "test_item_id",
                        "price" => 10000,
                        "count" => 3
                    ],
                    [
                        "item_id" => "test_item_id_2",
                        "price" => 30000,
                        "count" => 1
                    ]
                ],
            ],
            [
                // case 3: there is an order with count 0
                "user_id" => "test_user_id",
                "orders" => [
                    [
                        "item_id" => "test_item_id",
                        "price" => 10000,
                        "count" => 0
                    ],
                    [
                        "item_id" => "test_item_id_2",
                        "price" => 30000,
                        "count" => 1
                    ]
                ],
            ]
        ];
    }

    /**
     * @dataProvider shouldSucceedDataWithoutRequestOptions
     */
    public function testConstructorSucceedsWithoutOptions(
        $user_id,
        $item_id,
        $timestamp,
        $event_value,
        $from,
        $is_zai_recommendation
    ) {
        $expect = [
            "user_id" => "test_user_id",
            "item_id" => "test_item_id",
            "timestamp" => microtime(true),
            "event_value" => 1.0,
            "from" => "test_from",
            "is_zai_recommendation" => true,
        ];

        $request = new AddPurchaseEvent(
            $user_id,
            $item_id,
            $timestamp,
            $event_value,
            $from,
            $is_zai_recommendation
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expect),
            json_encode($request->getPayload())
        );
    }
}
