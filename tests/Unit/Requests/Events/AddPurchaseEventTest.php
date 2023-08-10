<?php
namespace ZaiClient\Tests\Requests\Events;

use PHPUnit\Framework\TestCase;

use ZaiClient\Utils\Util;
use ZaiClient\Requests\Events\AddPurchaseEvent;

class AddPurchaseEventTest extends TestCase
{
    public function shouldSucceedDataWithoutRequestOptions()
    {
        return [
            "case 1" => [
                // case 1: single order with 1 quantity
                "input" => [
                    "user_id" => "test_user_id",
                    "orders" => [
                        "item_id" => "test_item_id",
                        "price" => 10000,
                        "quantity" => 1
                    ],
                ],
                "expected" => [
                    "user_id" => "test_user_id",
                    "item_id" => "test_item_id",
                    "timestamp" => microtime(true),
                    "event_type" => "purchase",
                    "event_value" => "10000",
                    "from" => null,
                    "is_zai_recommendation" => false,
                    "time_to_live" => null,
                ],
            ],
            "case 2" => [
                // case 2: single order with 2 quantity
                "input" => [
                    "user_id" => "test_user_id",
                    "orders" => [
                        "item_id" => "test_item_id",
                        "price" => 10000,
                        "quantity" => 2
                    ],
                ],
                "expected" => [
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id",
                        "timestamp" => microtime(true),
                        "event_type" => "purchase",
                        "event_value" => "10000",
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ],
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id",
                        "timestamp" => microtime(true),
                        "event_type" => "purchase",
                        "event_value" => "10000",
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ]
                ],
            ],
            "case 3" => [
                // case 3: multiple orders
                "input" => [
                    "user_id" => "test_user_id",
                    "orders" => [
                        [
                            "item_id" => "test_item_id_1",
                            "price" => 10000,
                            "quantity" => 2
                        ],
                        [
                            "item_id" => "test_item_id_2",
                            "price" => 30000,
                            "quantity" => 2
                        ]
                    ],
                ],
                "expected" => [
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id_1",
                        "timestamp" => microtime(true),
                        "event_type" => "purchase",
                        "event_value" => "10000",
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ],
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id_1",
                        "timestamp" => microtime(true),
                        "event_type" => "purchase",
                        "event_value" => "10000",
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ],
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id_2",
                        "timestamp" => microtime(true),
                        "event_type" => "purchase",
                        "event_value" => "30000",
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ],
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id_2",
                        "timestamp" => microtime(true),
                        "event_type" => "purchase",
                        "event_value" => "30000",
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ]
                ],
            ],
            "case 4" => [
                // case 4: multiple orders with is_zai_rec flag
                "input" => [
                    "user_id" => "test_user_id",
                    "orders" => [
                        [
                            "item_id" => "test_item_id_1",
                            "price" => 10000,
                            "quantity" => 1,
                            "is_zai_rec" => true,
                        ],
                        [
                            "item_id" => "test_item_id_2",
                            "price" => 30000,
                            "quantity" => 1,
                            "is_zai_rec" => false,
                        ]
                    ],
                ],
                "expected" => [
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id_1",
                        "timestamp" => microtime(true),
                        "event_type" => "purchase",
                        "event_value" => "10000",
                        "from" => null,
                        "is_zai_recommendation" => true,
                        "time_to_live" => null,
                    ],
                    [
                        "user_id" => "test_user_id",
                        "item_id" => "test_item_id_2",
                        "timestamp" => microtime(true),
                        "event_type" => "purchase",
                        "event_value" => "30000",
                        "from" => null,
                        "is_zai_recommendation" => false,
                        "time_to_live" => null,
                    ],
                ],
            ],
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
                    "quantity" => 3
                ],
            ],
            [
                // case 2 : user_id is null
                "user_id" => null,
                "orders" => [
                    [
                        "item_id" => "test_item_id",
                        "price" => 10000,
                        "quantity" => 3
                    ],
                    [
                        "item_id" => "test_item_id_2",
                        "price" => 30000,
                        "quantity" => 1
                    ]
                ],
            ],
            [
                // case 3: there is an order with quantity 0
                "user_id" => "test_user_id",
                "orders" => [
                    [
                        "item_id" => "test_item_id",
                        "price" => 10000,
                        "quantity" => 0
                    ],
                    [
                        "item_id" => "test_item_id_2",
                        "price" => 30000,
                        "quantity" => 1
                    ]
                ],
            ]
        ];
    }

    /**
     * @dataProvider shouldSucceedDataWithoutRequestOptions
     */
    public function testConstructorSucceedsWithoutOptions(
        $input,
        $expected
    ) {
        $user_id = $input["user_id"];
        $orders = $input["orders"];
        $request = new AddPurchaseEvent(
            $user_id,
            $orders
        );

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
