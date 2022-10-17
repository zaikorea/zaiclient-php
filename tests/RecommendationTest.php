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
    const LIMIT_ERRMSG = 'Limit must be between 1 and 10,000.';
    const USER_ID_ERRMSG = 'Length of user id must be between 1 and 100 or null.';
    const ITEM_ID_ERRMSG = 'Length of item id must be between 1 and 100.';
    const LONG_OPTIONS_ERRMSG = "\$options['recommendation_options'] must be less than or equal to 1000 when converted to string";
    const OPTIONS_TYPE_ERRMSG = 'Options must be given as an array.';
    const REC_TYPE_ERRMSG = 'Length of recommendation type must be between 1 and 100.';
    const ITEM_IDS_ERRMSG = 'Length of item_ids must be between 1 and 10,000.';

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

        $expected_metadata = [
            'user_id' => isset($user_id) ? $user_id : null,
            'item_id' => isset($item_id) ? $item_id : null,
            'item_ids' => isset($item_ids) ? $item_ids : null,
            'limit' => isset($limit) ? $limit : null,
            'offset' => isset($options['offset']) ? $options['offset'] : $request::DEFAULT_OFFSET,
            'options' => isset($options['recommendation_options']) ? $options['recommendation_options'] : array(),
            'call_type' => substr($request::RECOMMENDER_PATH, 1),
            'recommendation_type' => isset($options['recommendation_type']) ? $options['recommendation_type'] : $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertEquals($response->getItems(), ['ITEM_ID_0', 'ITEM_ID_1', 'ITEM_ID_2']);
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertSame($response->getMetadata(), $expected_metadata);
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithUserRecommendationWithDefaults()
    {
        $user_id = 'user';
        $limit = 3;

        $request = new UserRecommendationRequest($user_id, $limit);

        self::assertSame($request->getOptions(), null);
        self::assertSame($request->getOffset(), 0);
        self::assertSame($request->getLimit(), $limit);
        self::assertSame($request->getRecommendationType(), 'homepage');
    }

    public function testGetRecommendationsWithUserRecommendationRequestWithNullUserId()
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

        $expected_metadata = [
            'user_id' => isset($user_id) ? $user_id : null,
            'item_id' => isset($item_id) ? $item_id : null,
            'item_ids' => isset($item_ids) ? $item_ids : null,
            'limit' => isset($limit) ? $limit : null,
            'offset' => isset($options['offset']) ? $options['offset'] : $request::DEFAULT_OFFSET,
            'options' => isset($options['recommendation_options']) ? $options['recommendation_options'] : array(),
            'call_type' => substr($request::RECOMMENDER_PATH, 1),
            'recommendation_type' => isset($options['recommendation_type']) ? $options['recommendation_type'] : $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertEquals($response->getItems(), ['ITEM_ID_0', 'ITEM_ID_1', 'ITEM_ID_2']);
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertSame($response->getMetadata(), $expected_metadata);
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

        $expected_metadata = [
            'user_id' => isset($user_id) ? $user_id : null,
            'item_id' => isset($item_id) ? $item_id : null,
            'item_ids' => isset($item_ids) ? $item_ids : null,
            'limit' => isset($limit) ? $limit : null,
            'offset' => isset($options['offset']) ? $options['offset'] : $request::DEFAULT_OFFSET,
            'options' => isset($options['recommendation_options']) ? $options['recommendation_options'] : array(),
            'call_type' => substr($request::RECOMMENDER_PATH, 1),
            'recommendation_type' => isset($options['recommendation_type']) ? $options['recommendation_type'] : $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertSame($response->getItems()[0], 'ITEM_ID_5');
        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertSame($response->getMetadata(), $expected_metadata);
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
            'recommendation_options' => $json_options
        ];

        $request = new UserRecommendationRequest($user_id, $limit, $options);
        $response = $client->getRecommendations($request);

        $expected_metadata = [
            'user_id' => isset($user_id) ? $user_id : null,
            'item_id' => isset($item_id) ? $item_id : null,
            'item_ids' => isset($item_ids) ? $item_ids : null,
            'limit' => isset($limit) ? $limit : null,
            'offset' => isset($options['offset']) ? $options['offset'] : $request::DEFAULT_OFFSET,
            'options' => isset($options['recommendation_options']) ? $options['recommendation_options'] : array(),
            'call_type' => substr($request::RECOMMENDER_PATH, 1),
            'recommendation_type' => isset($options['recommendation_type']) ? $options['recommendation_type'] : $request::DEFAULT_RECOMMENDATION_TYPE
        ];
        
        self::assertSame($response->getItems()[0], 'ITEM_ID_5');
        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertSame($response->getMetadata(), $expected_metadata);
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

        $expected_metadata = [
            'user_id' => isset($user_id) ? $user_id : null,
            'item_id' => isset($item_id) ? $item_id : null,
            'item_ids' => isset($item_ids) ? $item_ids : null,
            'limit' => isset($limit) ? $limit : null,
            'offset' => isset($options['offset']) ? $options['offset'] : $request::DEFAULT_OFFSET,
            'options' => isset($options['recommendation_options']) ? $options['recommendation_options'] : array(),
            'call_type' => substr($request::RECOMMENDER_PATH, 1),
            'recommendation_type' => isset($options['recommendation_type']) ? $options['recommendation_type'] : $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertSame($response->getMetadata(), $expected_metadata);
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithRelatedItemsRecommendationRequestWithDefaults()
    {

        $item_id = "012345567788";
        $limit = 10;

        $request = new RelatedItemsRecommendationRequest($item_id, $limit);

        self::assertSame($request->getOptions(), null);
        self::assertSame($request->getOffset(), 0);
        self::assertSame($request->getLimit(), $limit);
        self::assertSame($request->getRecommendationType(), 'product_detail_page');
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
            'recommendation_options' => $json_options
        ];

        $request = new RelatedItemsRecommendationRequest($item_id, $limit, $options);
        $response = $client->getRecommendations($request);

        $expected_metadata = [
            'user_id' => isset($user_id) ? $user_id : null,
            'item_id' => isset($item_id) ? $item_id : null,
            'item_ids' => isset($item_ids) ? $item_ids : null,
            'limit' => isset($limit) ? $limit : null,
            'offset' => isset($options['offset']) ? $options['offset'] : $request::DEFAULT_OFFSET,
            'options' => isset($options['recommendation_options']) ? $options['recommendation_options'] : array(),
            'call_type' => substr($request::RECOMMENDER_PATH, 1),
            'recommendation_type' => isset($options['recommendation_type']) ? $options['recommendation_type'] : $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertSame($response->getItems()[0], 'ITEM_ID_5');
        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertSame($response->getMetadata(), $expected_metadata);
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithRerankingRecommendationRequest()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = "ZaiTest_User_id";
        $item_ids = ["1234", "5678", "9101112"];
        $limit = 2;
        $options = [
            'recommendation_type' => 'all_products_page',
            'offset' => 0,
            'limit' => $limit
        ];

        $request = new RerankingRecommendationRequest($user_id, $item_ids, $options);
        $response = $client->getRecommendations($request);

        $expected_metadata = [
            'user_id' => isset($user_id) ? $user_id : null,
            'item_id' => isset($item_id) ? $item_id : null,
            'item_ids' => isset($item_ids) ? $item_ids : null,
            'limit' => isset($limit) ? $limit : null,
            'offset' => isset($options['offset']) ? $options['offset'] : $request::DEFAULT_OFFSET,
            'options' => isset($options['recommendation_options']) ? $options['recommendation_options'] : array(),
            'call_type' => substr($request::RECOMMENDER_PATH, 1),
            'recommendation_type' => isset($options['recommendation_type']) ? $options['recommendation_type'] : $request::DEFAULT_RECOMMENDATION_TYPE
        ];

        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertSame($response->getMetadata(), $expected_metadata);
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }

    public function testGetRecommendationsWithRerankingRecommendationRequestWithDefaults()
    {
        $user_id = "ZaiTest_User_id";
        $item_ids = ["1234", "5678", "9101112"];

        $request = new RerankingRecommendationRequest($user_id, $item_ids);

        self::assertSame($request->getOptions(), null);
        self::assertSame($request->getOffset(), 0);
        self::assertSame($request->getLimit(), count($item_ids));
        self::assertSame($request->getRecommendationType(), 'all_products_page');
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
            'recommendation_options' => $json_options
        ];

        $request = new RerankingRecommendationRequest($user_id, $item_ids, $options);
        $response = $client->getRecommendations($request);

        $expected_metadata = [
            'user_id' => isset($user_id) ? $user_id : null,
            'item_id' => isset($item_id) ? $item_id : null,
            'item_ids' => isset($item_ids) ? $item_ids : null,
            'limit' => isset($limit) ? $limit : null,
            'offset' => isset($options['offset']) ? $options['offset'] : $request::DEFAULT_OFFSET,
            'options' => isset($options['recommendation_options']) ? $options['recommendation_options'] : array(),
            'call_type' => substr($request::RECOMMENDER_PATH, 1),
            'recommendation_type' => isset($options['recommendation_type']) ? $options['recommendation_type'] : $request::DEFAULT_RECOMMENDATION_TYPE
        ];
        
        self::assertNotNull($response->getItems(), "items in response is null");
        self::assertSame($response->getCount(), $limit, "items count don't match");
        self::assertSame($response->getMetadata(), $expected_metadata);
        self::assertTrue(time() - $response->getTimestamp() < 0.5);
    }


    /* ------------------- Test Errors ---------------------  */
    public function testUserRecommendationWithEmptyUserId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::USER_ID_ERRMSG);

        $user_id = "";
        $limit = 50;

        $options = [
            'recommendation_type' => 'homepage',
            'offset' => 0
        ];
        new UserRecommendationRequest($user_id, $limit, $options);
    }

    public function testUserRecommendationWithLongUserId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::USER_ID_ERRMSG);

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
        $this->expectExceptionMessage(self::LIMIT_ERRMSG);

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
        $this->expectExceptionMessage(self::REC_TYPE_ERRMSG);

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
        $this->expectExceptionMessage(self::OPTIONS_TYPE_ERRMSG);

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
            'recommendation_options' => $json_options
        ];
        new UserRecommendationRequest($user_id, $limit, $options);
    }

    public function testRelatedItemsRecommendationWithNullItemId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::ITEM_ID_ERRMSG);

        $user_id = null;
        $limit = 10;

        $options = [
            'recommendation_type' => 'product_datail_page',
            'offset' => 0
        ];
        new RelatedItemsRecommendationRequest($user_id, $limit, $options);
    }

    public function testRelatedItemsRecommendationWithEmptyItemId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::ITEM_ID_ERRMSG);

        $user_id = "";
        $limit = 10;

        $options = [
            'recommendation_type' => 'product_datail_page',
            'offset' => 0
        ];
        new RelatedItemsRecommendationRequest($user_id, $limit, $options);
    }

    public function testRelatedItemsRecommendationWithNullLimit()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::LIMIT_ERRMSG);

        $user_id = "ZaiTest_User_id";
        $limit = null;

        $options = [
            'recommendation_type' => 'product_datail_page',
            'offset' => 0
        ];
        new RelatedItemsRecommendationRequest($user_id, $limit, $options);
    }

    public function testRelatedRecommendationWithNullItemId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::ITEM_ID_ERRMSG);

        $item_id = null; 
        $limit = 10;

        $options = [
            'recommendation_type' => 'product_datail_page',
            'offset' => 0
        ];
        new RelatedItemsRecommendationRequest($item_id, $limit, $options);
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

    public function testRerankingRecommendationsWithEmptyUserId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::USER_ID_ERRMSG);

        $user_id = "";
        $item_ids = [];
        $options = [
            'recommendation_type' => 'all_products_page',
            'offset' => 0
        ];

        new RerankingRecommendationRequest($user_id, $item_ids, $options);
    }

    public function testRerankingRecommendationWithEmptyItemIds()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::ITEM_IDS_ERRMSG);

        $user_id = "ZaiTest_User_id";
        $item_ids = [];
        $options = [
            'recommendation_type' => 'all_products_page',
            'offset' => 0
        ];

        new RerankingRecommendationRequest($user_id, $item_ids, $options);
    }

    public function testRerankingRecommendationWithLargeLimit()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::LIMIT_ERRMSG);

        $user_id = "ZaiTest_User_id";
        $item_ids = ['1234', '1234', '1234'];
        $options = [
            'limit' => 100000000,
            'recommendation_type' => 'all_products_page',
            'offset' => 0
        ];

        new RerankingRecommendationRequest($user_id, $item_ids, $options);
    }

}
