<?php
namespace ZaiClient\Tests\Requests;

use PHPUnit\Framework\TestCase;

use BadMethodCallException;
use ZaiClient\Requests\Recommendations\RecommendationRequest;

class RecommendationRequestTest extends TestCase
{
    public function testGetPayloadSucceeds()
    {
        $expected = [
            "user_id" => "user_id",
            "item_id" => "item_id",
            "item_ids" => ["item_id1", "item_id2"],
            "recommendation_type" => "recommendation_type",
            "limit" => 10,
            "offset" => 0,
            "options" => null
        ];

        $request = new RecommendationRequest(
            "user_id",
            "item_id",
            ["item_id1", "item_id2"],
            "recommendation_type",
            10,
            0,
            null
        );

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected),
            json_encode($request->getPayload())
        );
    }

    public function testGetPathFails()
    {
        $this->expectException(BadMethodCallException::class);

        $request = new RecommendationRequest(
            "user_id",
            "item_id",
            ["item_id1", "item_id2"],
            "recommendation_type",
            10,
            0,
            null
        );

        $request->getPath("test_client_id");
    }

    public function testGetQueryParamFails()
    {
        $this->expectException(BadMethodCallException::class);

        $request = new RecommendationRequest(
            "user_id",
            "item_id",
            ["item_id1", "item_id2"],
            "recommendation_type",
            10,
            0,
            null
        );

        $request->getQueryParams();
    }
}
