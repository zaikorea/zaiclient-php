<?php

/**
 * ZaiClientTest
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiClient;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Items\ItemRequest;
use ZaiClient\Requests\ProductDetailViewEvent;
use ZaiClient\Tests\TestUtils;

class ZaiclientTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';
    private $add_event_msg = 'The given event was added successfully.';
    private $mockHttpClient = null;

    /**
     * @before
     */
    public function setUpTestClass()
    {
        $this->mockHttpClient = TestUtils::createMockHttpClient($this);
    }

    /**
     * @after
     */
    public function teardownTestClass()
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

        // Test With ItemRequest
        $request = new ItemRequest("POST", "P1000005", "test-item");

        $response = $client->sendRequest($request);

        $response_body = $response->getBody();

        // To consume the stream, read the first 1024 bytes.
        // If you want to retrieve the entire contents of the stream, use getContents() instead of read().
        self::assertJsonStringEqualsJsonString(TestUtils::getDefaultResponseBody(), $response_body->getContents());
    }

    public function testSendRequestWithCustomEndPoint()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options = ["custom_endpoint" => "test-dev"], $this->mockHttpClient);

        // Test With ItemRequest
        $request = new ItemRequest("POST", "P1000005", "test-item");


        $endpoint = sprintf($request->getBaseUrl(), $client->getOptions()['custom_endpoint']) . $request->getPath(null);

        self::assertSame("https://collector-api-test-dev.zaikorea.org/items", $endpoint);
    }
}
