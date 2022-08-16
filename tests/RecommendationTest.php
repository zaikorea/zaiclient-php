<?php

namespace ZaiKorea\ZaiClient;

use PHPUnit\Framework\TestCase;
use ZaiKorea\ZaiClient\Requests\UserRecommendationRequest;
use ZaiKorea\ZaiClient\Requests\RelatedItemsRecommendationRequest;
use ZaiKorea\ZaiClient\Requests\RerankingRecommendationRequest;

require_once 'TestUtils.php';

class RecommendationTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';
    private $client_id = 'test';

    public function testGetRecommendationsWithUserRecommendationRequest()
    {

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = 'user';
        $limit = 3;
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];

        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertEquals($response->getItems(), ['user_homepage_ITEM_ID_0', 'user_homepage_ITEM_ID_1', 'user_homepage_ITEM_ID_2']);
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithUserRecommendationRequestWithNull()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = null;
        $limit = 3;
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];

        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertEquals($response->getItems(), ['None_homepage_ITEM_ID_0', 'None_homepage_ITEM_ID_1', 'None_homepage_ITEM_ID_2']);
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithUserRecommendationRequestWithOffset()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = 'testing';
        $limit = 10;
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 5
        ];

        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertSame($response->getItems()[0], 'testing_homepage_ITEM_ID_5');
        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithRelatedItemsRecommendationRequest()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $item_id = "012345567788";
        $limit = 10;
        $options = [
            'recommendation_type' => 'product_detail_page',
            'offset' => 0
        ];

        $request = new RelatedItemsRecommendationRequest($item_id, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithRerankingRecommendationRequest()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = "ZaiTest_User_id";
        $item_ids = ["1234", "5678", "9101112"];
        $limit = 3;
        $options = [
            'recommendation_type' => 'all_products_page',
            'offset' => 0
        ];

        $request = new RerankingRecommendationRequest($user_id, $item_ids, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    /* ------------------- Test Errors ---------------------  */
    public function testUserRecommendationWithNullLimit()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 1000,000.');

        $user_id = "ZaiTest_User_id";
        $limit = null;

        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];
        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $response = $client->getRecommendations($request);
    }

    public function testRelatedItemsRecommendationWithNullLimit()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 1000,000.');

        $user_id = "ZaiTest_User_id";
        $limit = null;

        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];
        $request = new RelatedItemsRecommendationRequest($user_id, $limit, $options);
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $response = $client->getRecommendations($request);
    }

    public function testRelatedRecommendationWithNullItemId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Length of item id must be between 1 and 100.');

        $item_id = null; 
        $limit = 10;

        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];
        $request = new RelatedItemsRecommendationRequest($item_id, $limit, $options);
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $response = $client->getRecommendations($request);
    }

    public function testRerankingRecommendationWithEmptyItemIds()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Length of item_ids must be between 1 and 1000,000.');

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = "ZaiTest_User_id";
        $item_ids = [];
        $limit = 3;
        $options = [
            'recommendation_type' => 'all_products_page',
            'offset' => 0
        ];

        $request = new RerankingRecommendationRequest($user_id, $item_ids, $limit, $options);
        $response = $client->getRecommendations($request);
    }

    public function testUserRecommendationWithNullRecommendationType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Length of recommendation type must be between 1 and 100.');

        $user_id = "LongRecommendationType";
        $limit = 3;

        $options = [
            'recommendation_type' => generateRandomString(101),
        ];
        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $response = $client->getRecommendations($request);
    }

    public function testUserRecommendationWithNoneArrayOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Options must be given as an array.');

        $user_id = "ZaiTest_User_id";
        $limit = 3;

        $options = 'homepage';
        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $response = $client->getRecommendations($request);
    }
}
