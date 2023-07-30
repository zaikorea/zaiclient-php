<?php

/**
 * ZaiClientTest
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiClient;

use PHPUnit\Framework\TestCase;
use ZaiClient\Requests\PurchaseEvent;
use ZaiClient\Requests\ProductDetailViewEvent;
use ZaiClient\Requests\PageViewEvent;
use ZaiClient\Requests\SearchEvent;
use ZaiClient\Requests\LikeEvent;
use ZaiClient\Requests\CartaddEvent;
use ZaiClient\Requests\RateEvent;
use ZaiClient\Requests\CustomEvent;
use ZaiClient\Exceptions\ZaiClientException;
use ZaiClient\Exceptions\BatchSizeLimitExceededException;
use ZaiClient\Configs\Config;

class ZaiclientTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';
    private $add_event_msg = 'The given event was added successfully.';
    private $mockHttpClient = null;

    /**
     * @beforeClass
     */
    protected function setUpTestClass()
    {
        $this->mockHttpClient = TestUtils::createMockHttpClient($this);

    }

    /**
     * @afterClass
     */
    protected function teardownTestClass()
    {
        $this->mockHttpClient = null;
    }

    public function testClient()
    {

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $user_id = 'php-testClient';
        $item_id = 'P1000005';
        $product_detail_view_event = new ProductDetailViewEvent($user_id, $item_id);
        $response = $client->addEventLog($product_detail_view_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testClientWithWrongTimeoutOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $options = [
            'connection_timeout' => "13",
            'read_timeout' => "2"
        ];

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options);
    }

    public function testClientWithTimeoutOptions()
    {
        $options = [
            'connect_timeout' => 60,
            'read_timeout' => 60
        ];
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options);
        self::assertSame($client->getOptions()['connect_timeout'], 60);
        self::assertSame($client->getOptions()['read_timeout'], 60);

        $user_id = 'php-add-single-productdetailview';
        $item_id = 'P1000005';

        $product_detail_view_event = new ProductDetailViewEvent($user_id, $item_id);
        $response = $client->addEventLog($product_detail_view_event);

        self::assertSame($this->add_event_msg, $response->getMessage());
    }

    public function testClientWithCustomEndpoint()
    {
        $options = [
            'custom_endpoint' => 'dev',
        ];
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options);
        self::assertSame($client->getCollectorApiEndpoint(), 'https://collector-api-dev.zaikorea.org');
        self::assertSame($client->getMlApiEndpoint(), 'https://ml-api-dev.zaikorea.org');
    }

    public function testClientWithBadCustomEndpoint()
    {
        $this->expectException(\InvalidArgumentException::class);

        $options = [
            'custom_endpoint' => '-@dev',
        ];
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options);
    }

    public function testSendRequest()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options = array(), $this->mockHttpClient);
        $mock_request = [
            "method" => "POST",
            "body" => [
                "foo" => "bar",
                "baz" => "qux",
            ],
        ];

        $response_json = $client->sendRequest($mock_request);

        self::assertJsonStringEqualsJsonFile(TestUtils::getDefaultResponseBody(), $response_json);
    }

}
