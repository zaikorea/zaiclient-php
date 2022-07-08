<?php
namespace ZaiKorea\ZaiClient;

use PHPUnit\Framework\TestCase;
use ZaiKorea\ZaiClient\Requests\UserRecommendationRequest;
use ZaiKorea\ZaiClient\Requests\RelatedItemsRecommendationRequest;
use ZaiKorea\ZaiClient\Requests\RerankingRecommendationRequest;


class RecommendationTest extends TestCase {
    private $client_id = 'test';
    private $client_secret = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k'; // secret key for testing

    public function testGetRecommendationsWithUserRecommendationRequest() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $user_id = 'ZaiTest_User_id';
        $limit = 10;
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];

        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5 );
    }

    public function testGetRecommendationsWithUserRecommendationRequestWithNull() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $user_id = null;
        $limit = 10;
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];

        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5 );
    }

    public function testGetRecommendationsWithUserRecommendationRequestWithOffset() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
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
        self::assertTrue(time() - $response->getTimestamp() < 0.5 );
    }

    public function testGetRecommendationsWithRelatedItemsRecommendationRequest() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
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
        self::assertTrue(time() - $response->getTimestamp() < 0.5 );
    }

    public function testGetRecommendationsWithRerankingRecommendationRequest() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
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
        self::assertTrue(time() - $response->getTimestamp() < 0.5 );
    }

}
?>
