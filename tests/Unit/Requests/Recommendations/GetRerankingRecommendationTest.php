<?php
namespace ZaiClient\Tests\Requests\Recommendations;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use ZaiClient\Tests\TestUtils;
use ZaiClient\Requests\Recommendations\GetRerankingRecommendation;



class GetRerankingRecommendationTest extends TestCase
{
    public function shouldSucceedDataWithoutOptions()
    {
        return [
            [
                "user_id" => TestUtils::generateRandomString(100),
                "item_ids" => TestUtils::generateRandomArrayOfUuid(10),
            ],
        ];
    }

    public function shouldFailDataWithoutOptions()
    {
        return [
            [
                "user_id" => TestUtils::generateRandomString(100),
                "item_ids" => TestUtils::generateRandomArrayOfUuid(10001),
            ],
            [
                "user_id" => TestUtils::generateRandomString(100),
                "item_ids" => null,
            ],
        ];
    }

    public function shouldSucceedDataWithOptions()
    {
        return [
            // Todo: add test cases
        ];
    }

    public function shouldFailDataWithOptions()
    {
        return [
            // Todo: add test cases
        ];
    }

    /**
     * @dataProvider shouldSucceedDataWithoutOptions
     */
    public function testConstructorWithoutOptionsSucceed(
        $user_id,
        $item_ids
    ) {
        $expected = [
            "user_id" => $user_id,
            "item_id" => null,
            "item_ids" => $item_ids,
            "recommendation_type" => "category",
            "limit" => count($item_ids),
            "offset" => 0,
            "options" => null,
        ];

        $request = new GetRerankingRecommendation(
            $user_id,
            $item_ids
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            json_encode($request->getPayload())
        );
    }

    /**
     * @dataProvider shouldFailDataWithoutOptions
     */
    public function testConstructorWithoutOptionsFail(
        $user_id,
        $item_ids
    ) {
        $this->expectException(InvalidArgumentException::class);

        new GetRerankingRecommendation(
            $user_id,
            $item_ids
        );

    }
}
