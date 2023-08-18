<?php
namespace ZaiClient;

use PHPUnit\Framework\TestCase;
use ZaiClient\Requests\Recommendations\GetUserRecommendation;
use ZaiClient\Requests\Recommendations\GetRelatedRecommendation;
use ZaiClient\Requests\Recommendations\GetRerankingRecommendation;

use ZaiClient\Tests\TestUtils;

class RecommendationsTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';

    const ASSERT_MSG_METADATA = "metadata don't match";
    const ASSERT_MSG_COUNT = "items count don't match";

    public function testGetUserRecommendation()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new GetUserRecommendation('user_1', 3, [
            'recommendation_options' => [
                "key" => "value"
            ]
        ]);

        $response = $client->sendRequest($request);

        $expected_metatdata = [
            'user_id' => "user_1",
            'item_id' => null,
            'item_ids' => null,
            'limit' => 3,
            'offset' => $request::DEFAULT_OFFSET,
            'options' => ["key" => "value"],
            'call_type' => "user-recommendations",
            'recommendation_type' => $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertEquals(['ITEM_ID_0', 'ITEM_ID_1', 'ITEM_ID_2'], $response->getItems());
        self::assertEquals(3, $response->getCount(), self::ASSERT_MSG_COUNT);
        self::assertEquals($expected_metatdata, $response->getMetadata(), self::ASSERT_MSG_METADATA);

    }

    public function testGetRelatedRecommendation()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new GetRelatedRecommendation('item_1', 3, [
            'recommendation_options' => [
                "key" => "value"
            ]
        ]);

        $response = $client->sendRequest($request);

        $expected_metatdata = [
            'user_id' => null,
            'item_id' => "item_1",
            'item_ids' => null,
            'limit' => 3,
            'offset' => $request::DEFAULT_OFFSET,
            'options' => ["key" => "value"],
            'call_type' => "related-items",
            'recommendation_type' => $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertEquals(['ITEM_ID_0', 'ITEM_ID_1', 'ITEM_ID_2'], $response->getItems());
        self::assertEquals(3, $response->getCount(), self::ASSERT_MSG_COUNT);
        self::assertEquals($expected_metatdata, $response->getMetadata(), self::ASSERT_MSG_METADATA);
    }

    public function testGetRerankingRecommendation()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new GetRerankingRecommendation(
            'user_1',
            ["ITEM_ID_0", "ITEM_ID_1", "ITEM_ID_2"],
            [
                'recommendation_options' => [
                    "key" => "value"
                ]
            ]
        );

        $response = $client->sendRequest($request);

        $expected_metatdata = [
            'user_id' => 'user_1',
            'item_id' => null,
            'item_ids' => ['ITEM_ID_0', 'ITEM_ID_1', 'ITEM_ID_2'],
            'limit' => 3,
            'offset' => $request::DEFAULT_OFFSET,
            'options' => ["key" => "value"],
            'call_type' => "reranking",
            'recommendation_type' => $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertEquals(['ITEM_ID_0', 'ITEM_ID_1', 'ITEM_ID_2'], $response->getItems());
        self::assertEquals(3, $response->getCount(), self::ASSERT_MSG_COUNT);
        self::assertEquals($expected_metatdata, $response->getMetadata(), self::ASSERT_MSG_METADATA);
    }


}
