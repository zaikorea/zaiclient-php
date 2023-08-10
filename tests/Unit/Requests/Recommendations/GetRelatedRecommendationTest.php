<?php
namespace ZaiClient\Tests\Requests\Recommendations;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;
use ZaiClient\Tests\TestUtils;
use ZaiClient\Requests\Recommendations\GetRelatedRecommendation;

class GetRelatedRecommendationTest extends TestCase
{
    public function shouldSucceedDataWithoutOptions()
    {
        return [
            [
                "item_id" => TestUtils::generateRandomString(100),
                "limit" => 10,
            ],
        ];
    }

    public function shouldFailDataWithoutOptions()
    {
        return [
            [
                "item_id" => null,
                "limit" => 10,
            ],
        ];
    }

    public function shouldSucceedWithOptions()
    {
        return [
            [
                "item_id" => TestUtils::generateRandomString(100),
                "limit" => 10,
                "request_options" => [
                    "recommendation_type" => "popular",
                    "offset" => 10,
                ]
            ],
            [
                "item_id" => TestUtils::generateRandomString(100),
                "limit" => 10,
                "request_options" => [
                    "recommendation_options" => [
                        "test_option_1" => "test_value_1",
                        "test_option_2" => "test_value_2",
                    ]
                ]
            ]
        ];
    }

    public function shouldFailWithOptions()
    {
        return [
            [
                "item_id" => TestUtils::generateRandomString(100),
                "limit" => 10,
                "request_options" => [
                    "recommendation_type" => "popular",
                    "offset" => 10001,
                ]
            ],
            [
                "item_id" => TestUtils::generateRandomString(100),
                "limit" => 10,
                "request_options" => [
                    "recommendation_type" => "popular",
                    "offset" => -1,
                ]
            ],
            [
                "item_id" => TestUtils::generateRandomString(100),
                "limit" => 10,
                "request_options" => [
                    "recommendation_type" => TestUtils::generateRandomString(10),
                    "offset" => -1,
                ]
            ],
            [
                "item_id" => TestUtils::generateRandomString(100),
                "limit" => 10,
                "request_options" => [
                    "recommendation_type" => TestUtils::generateRandomString(501),
                    "offset" => 0,
                ]
            ],
        ];
    }

    /**
     * @dataProvider shouldSucceedDataWithoutOptions
     */
    public function testConstructorSucceedWithOutOptions(
        $item_id,
        $limit
    ) {
        $expected = [
            "user_id" => null,
            "item_id" => $item_id,
            "item_ids" => null,
            "recommendation_type" => "product_detail_page",
            "limit" => $limit,
            "offset" => 0,
            "options" => null,
        ];

        $request = new GetRelatedRecommendation(
            $item_id,
            $limit
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            json_encode($request->getPayload())
        );
    }

    /**
     * @dataProvider shouldFailDataWithoutOptions
     */
    public function testConstructorFailWithoutOptions(
        $item_id,
        $limit
    ) {
        $this->expectException(InvalidArgumentException::class);

        new GetRelatedRecommendation(
            $item_id,
            $limit
        );
    }

    /**
     * @dataProvider shouldSucceedWithOptions
     */
    public function testConstructorSucceedWithOptions(
        $item_id,
        $limit,
        $request_options
    ) {
        $offset = 0;
        $recommendation_type = "product_detail_page";
        $recommendation_options = null;

        if (!is_null($request_options)) {
            if (array_key_exists("recommendation_type", $request_options)) {
                $recommendation_type = $request_options["recommendation_type"];
            }

            if (array_key_exists("offset", $request_options)) {
                $offset = $request_options["offset"];
            }

            if (array_key_exists("recommendation_options", $request_options)) {
                $recommendation_options = $request_options["recommendation_options"];
            }

        }

        $expected = [
            "user_id" => null,
            "item_id" => $item_id,
            "item_ids" => null,
            "recommendation_type" => $recommendation_type,
            "limit" => $limit,
            "offset" => $offset,
            "options" => is_null($recommendation_options) ? $recommendation_options : json_encode($recommendation_options),
        ];

        $request_options = new GetRelatedRecommendation(
            $item_id,
            $limit,
            $request_options
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            json_encode($request_options->getPayload())
        );
    }

    /**
     * @dataProvider shouldFailWithOptions
     */
    public function testConstructorFailWithOptions(
        $item_id,
        $limit,
        $request_options
    ) {
        $this->expectException(InvalidArgumentException::class);

        $offset = 0;
        $recommendation_type = "product_detail_page";
        $recommendation_options = null;

        if (!is_null($request_options)) {
            if (array_key_exists("recommendation_type", $request_options)) {
                $recommendation_type = $request_options["recommendation_type"];
            }

            if (array_key_exists("offset", $request_options)) {
                $offset = $request_options["offset"];
            }

            if (array_key_exists("recommendation_options", $request_options)) {
                $recommendation_options = $request_options["recommendation_options"];
            }

        }

        new GetRelatedRecommendation(
            $item_id,
            $limit,
            $request_options
        );
    }
}
