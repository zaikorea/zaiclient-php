<?php
namespace ZaiClient\Tests\Requests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use ZaiClient\Tests\TestUtils;
use ZaiClient\Requests\Recommendations\Recommendation;

class RecommendationTest extends TestCase
{
    public function shouldSucceed()
    {
        return [
            [
                "User_Id_1",
                "Item_Id_1",
                ["Item_Id_1"],
                "test_recommendation_type",
                10,
                0,
                [
                    "test_option_1" => "test_value_1",
                    "test_option_2" => "test_value_2",
                ]
            ],
            [
                "User_Id_1",
                "Item_Id_1",
                ["Item_Id_1"],
                "test_recommendation_type",
                10,
                0,
                null
            ],
        ];
    }

    public function shouldFail()
    {
        return [
            [
                TestUtils::generateRandomString(4000),
                "Item_Id_1",
                ["Item_Id_1"],
                "test_recommendation_type",
                10000,
                0,
                [
                    "test_option_1" => "test_value_1",
                    "test_option_2" => "test_value_2",
                ],
            ],
            [
                "User_Id_1",
                TestUtils::generateRandomString(0),
                ["Item_Id_1"],
                "test_recommendation_type",
                1000,
                10,
                null
            ]
        ];
    }

    /* ----------------------- Happy Path ----------------------- */

    /**
     * @dataProvider shouldSucceed
     */
    public function testClassConstructor(
        $user_id,
        $item_id,
        $item_ids,
        $recommendation_type,
        $limit,
        $offset,
        $options
    ) {
        $expected = json_encode(
            [
                "user_id" => $user_id,
                "item_id" => $item_id,
                "item_ids" => $item_ids,
                "recommendation_type" => $recommendation_type,
                "limit" => $limit,
                "offset" => $offset,
                "options" => $options
            ]
        );

        $recommendation = new Recommendation(
            $user_id,
            $item_id,
            $item_ids,
            $recommendation_type,
            $limit,
            $offset,
            $options
        );

        $actual = json_encode($recommendation);

        $this->assertEquals($expected, $actual);
    }

    /* --------------------- Unhappy Path ----------------------- */

    /**
     * @dataProvider shouldFail
     */
    public function testClassConstructorWithFailureData(
        $user_id,
        $item_id,
        $item_ids,
        $recommendation_type,
        $limit,
        $offset,
        $options
    ) {
        $this->expectException(InvalidArgumentException::class);

        new Recommendation(
            $user_id,
            $item_id,
            $item_ids,
            $recommendation_type,
            $limit,
            $offset,
            $options
        );
    }

}
