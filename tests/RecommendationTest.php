<?php
namespace ZaiKorea\ZaiClient;

use PHPUnit\Framework\TestCase;
use ZaiKorea\ZaiClient\Requests\UserRecommendationRequest;


class RecommendationTest extends TestCase {
    private $client_id = "test";
    private $client_secret = "KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k"; // secret key for testing

    public function testGetRecommendationsWithUserRecommendationRequest() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $user_id = "ZaiTest_User_id";
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

    public function testGetRecommendationsWithRelatedItemsRecommendationRequest() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $user_id = "ZaiTest_User_id";
        $limit = 10;
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];

        // TODO
        $request = new RelatedItemsRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5 );
    }

    public function testGetRecommendationsWithRerankingRecommendationRequest() {
        $client = new ZaiClient($this->client_id, $this->client_secret);
        $user_id = "ZaiTest_User_id";
        $limit = 10;
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];

        // TODO
        $request = new RerankingRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5 );
    }

}
?>
