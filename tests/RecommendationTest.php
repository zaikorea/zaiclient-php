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
    const LARGE_LIMIT_ERRMSG = 'Limit must be between 0 and 1000,000.';
    const ITEM_ID_ERRMSG = 'Length of item id must be between 1 and 100.';
    const LONG_OPTIONS_ERRMSG = "\$options['options'] must be less than 1000 when converted to string";

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

    public function testGetRecommendationsWithUserRecommendationRequestWithOptions()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = 'testing';
        $limit = 10;
        $json_options = [
            '123' => 1,
            '1234' => 'opt_1'
        ];
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 5,
            'options' => $json_options
        ];

        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);
        
        self::assertSame($request->getRecommendationType(), 'homepage');
        self::assertSame($request->getOptions(), '{"123":1,"1234":"opt_1"}');
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

    public function testGetRecommendationsWithRelatedItemsRecommendationRequestWithOptions()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $item_id = "012345567788";
        $limit = 10;
        $json_options = [
            '123' => 1,
            '1234' => 'opt_1'
        ];
        $options = [
            'recommendation_type' => 'product_detail_page',
            'offset' => 5,
            'options' => $json_options
        ];

        $request = new RelatedItemsRecommendationRequest($item_id, $limit, $options);
        $response = $client->getRecommendations($request);
        
        self::assertSame($request->getRecommendationType(), 'product_detail_page');
        self::assertSame($request->getOptions(), '{"123":1,"1234":"opt_1"}');
        self::assertSame($response->getItems()[0], $item_id . '_product_detail_page_ITEM_ID_5');
        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithRerankingRecommendationRequest()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = "ZaiTest_User_id";
        $item_ids = ["1234", "5678", "9101112"];
        $limit = count($item_ids);
        $options = [
            'recommendation_type' => 'all_products_page',
            'offset' => 0
        ];

        $request = new RerankingRecommendationRequest($user_id, $item_ids, $options);
        $response = $client->getRecommendations($request);

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithRerankingRecommendationRequestWithOptions()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = "ZaiTest_User_id";
        $item_ids = ["1234", "5678", "9101112"];
        $limit = count($item_ids);
        $json_options = [
            '123' => 1,
            '1234' => 'opt_1'
        ];
        $options = [
            'recommendation_type' => 'all_products_page',
            'offset' => 0,
            'options' => $json_options
        ];

        $request = new RerankingRecommendationRequest($user_id, $item_ids, $options);
        $response = $client->getRecommendations($request);
        
        self::assertSame($request->getRecommendationType(), 'all_products_page');
        self::assertSame($request->getOptions(), '{"123":1,"1234":"opt_1"}');
        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }


    /* ------------------- Test Errors ---------------------  */
    public function testUserRecommendationWithLongUserId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Length of user id must be between 1 and 100.');

        $user_id = str_repeat('testing', 100);
        $limit = 50;

        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];
        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $response = $client->getRecommendations($request);
    }

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
        new UserRecommendationRequest($user_id, $limit, $options);
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
        new UserRecommendationRequest($user_id, $limit, $options);
    }

    public function testUserRecommendationWithNoneArrayOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Options must be given as an array.');

        $user_id = "ZaiTest_User_id";
        $limit = 3;

        $options = 'homepage';
        new UserRecommendationRequest($user_id, $limit, $options);
    }

    public function testUserRecommendationWithLongOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::LONG_OPTIONS_ERRMSG);

        $user_id = 'testing';
        $json_options = array();
        for ($i = 1; $i < 1001; $i++) {
            $json_options[strval($i)] = $i;
        }
        $limit = 10;
        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 5,
            'options' => $json_options
        ];

        
        new UserRecommendationRequest($user_id, $limit, $options);
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

    public function testRelatedRecommendationWithLongItemId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::ITEM_ID_ERRMSG);

        $item_id = str_repeat('1234', 1000); 
        $limit = 10;

        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];
        new RelatedItemsRecommendationRequest($item_id, $limit, $options);
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

        new RerankingRecommendationRequest($user_id, $item_ids, $options);
    }

    public function testRerankingRecommendationWithLargeLimit()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::LARGE_LIMIT_ERRMSG);

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = "ZaiTest_User_id";
        $item_ids = ['1234', '1234', '1234'];
        $limit = 3;
        $options = [
            'limit' => 100000000,
            'recommendation_type' => 'all_products_page',
            'offset' => 0
        ];

        new RerankingRecommendationRequest($user_id, $item_ids, $options);
    }

}
