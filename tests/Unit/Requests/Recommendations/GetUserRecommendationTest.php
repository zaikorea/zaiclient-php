<?php
namespace ZaiClient\Tests\Requests\Recommendations;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ZaiClient\Requests\Recommendations\GetUserRecommendation;
use ZaiClient\Tests\TestUtils;

class GetUserRecommendationTest extends TestCase
{
    public function shouldSucceedDataWithoutOptions()
    {
        return [
            [
                "user_id" => null,
                "limit" => 10,
            ],
            [
                "user_id" => TestUtils::generateRandomString(100),
                "limit" => 10,
            ]
        ];
    }

    public function shouldFailDataWithoutOptions()
    {
        return [
            [
                "user_id" => null,
                "limit" => null,
            ],
            [
                "user_id" => null,
                "limit" => 10001,
            ],
            [
                "user_id" => null,
                "limit" => 0,
            ],
            [
                "user_id" => TestUtils::generateRandomString(501),
                "limit" => null,
            ],
            [
                "user_id" => TestUtils::generateRandomString(0),
                "limit" => null,
            ],
        ];
    }

    public function shouldSucceedWithOptions()
    {
        return [
            [
                "user_id" => null,
                "limit" => 10,
                "request_options" => [
                    "recommendation_type" => "popular"
                ]
            ],
            [
                "user_id" => null,
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
                "user_id" => null,
                "limit" => 10,
                "request_options" => null
            ],
            [
                "user_id" => null,
                "limit" => 10,
                "request_options" => [
                    "recommendation_type" => "popular",
                    "offset" => 10001
                ],
            ],
            [
                "user_id" => null,
                "limit" => 10,
                "request_options" => [
                    "recommendation_options" => [
                        "not associated array element",
                        "not associated array element",
                    ]
                ]
            ],

            [
                "user_id" => null,
                "limit" => 10,
                "request_options" => [
                    "recommendation_options" => "Should fail because it is not an array"
                ]
            ]
        ];
    }

    /**
     * @dataProvider shouldSucceedDataWithoutOptions
     */
    public function testConstructorSucceedWithOutOptions(
        $user_id,
        $limit
    ) {
        $expected = [
            "user_id" => $user_id,
            "item_id" => null,
            "item_ids" => null,
            "recommendation_type" => "homepage",
            "limit" => $limit,
            "offset" => 0,
            "options" => null,
        ];

        $request = new GetUserRecommendation(
            $user_id,
            $limit
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            json_encode($request->getPayload())
        );
    }

    /**
     * @dataProvider shouldFailDataWithOutOptions
     */
    public function testConstructorFailWithOutOptions(
        $user_id,
        $limit
    ) {
        $this->expectException(InvalidArgumentException::class);

        $expected = [
            "user_id" => $user_id,
            "item_id" => null,
            "item_ids" => null,
            "recommendation_type" => "homepage",
            "limit" => $limit,
            "offset" => 0,
            "options" => null,
        ];

        new GetUserRecommendation(
            $user_id,
            $limit
        );

    }

    /**
     *  @dataProvider shouldSucceedWithOptions
     */
    public function testConstructorSucceedWithOptions(
        $user_id,
        $limit,
        $request_options = array())
    {
        $offset = 0;
        $recommendation_type = "homepage";
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
            "user_id" => $user_id,
            "item_id" => null,
            "item_ids" => null,
            "recommendation_type" => $recommendation_type,
            "limit" => $limit,
            "offset" => $offset,
            "options" => is_null($recommendation_options) ? $recommendation_options : json_encode($recommendation_options),
        ];

        $request = new GetUserRecommendation(
            $user_id,
            $limit,
            $request_options
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            json_encode($request->getPayload())
        );
    }
    /**
     *  @dataProvider shouldFailWithOptions
     */
    public function testConstructorFailWithOptions(
        $user_id,
        $limit,
        $request_options = array())
    {
        $this->expectException(InvalidArgumentException::class);

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

        new GetUserRecommendation(
            $user_id,
            $limit,
            $request_options
        );

    }
}
