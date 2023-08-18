<?php

/**
 * ZaiClientTest
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace ZaiClient;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Events\EventRequest;
use ZaiClient\Requests\Items\Item;
use ZaiClient\Requests\Items\ItemRequest;
use ZaiClient\Requests\Recommendations\GetUserRecommendation;
use ZaiClient\Requests\Recommendations\RecommendationRequest;
use ZaiClient\Tests\TestUtils;

class ZaiclientTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';
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

    // BackLog: Add tests using MockHttpClient

    public function testClientWithWrongTimeoutOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $options = [
            'connection_timeout' => "13",
            'read_timeout' => "2"
        ];

        new ZaiClient(self::CLIENT_ID, self::SECRET, $options); // NOSONAR
    }

    public function testClientWithTimeoutOptions()
    {
        $options = [
            'connect_timeout' => 60,
            'read_timeout' => 60
        ];

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options);

        self::assertSame(60, $client->getOptions()['connect_timeout']);
        self::assertSame(60, $client->getOptions()['read_timeout']);
    }

    public function testClientWithCustomEndpoint()
    {
        $options = [
            'custom_endpoint' => 'dev',
        ];
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, $options);
        self::assertSame('https://collector-api-dev.zaikorea.org', $client->getCollectorApiEndpoint());
        self::assertSame('https://ml-api-dev.zaikorea.org', $client->getMlApiEndpoint());
    }

    public function testClientWithBadCustomEndpoint()
    {
        $this->expectException(\InvalidArgumentException::class);

        $options = [
            'custom_endpoint' => '-@dev',
        ];

        new ZaiClient(self::CLIENT_ID, self::SECRET, $options); // NOSONAR
    }

    public function testSendRequest()
    {
        $mockBody = json_encode([
            "items" =>
                [array_merge(
                    TestUtils::getEmptyItemRequestPayload(),
                    [
                        "item_id" => "test_id",
                        "item_name" => "test-item",
                        "is_active" => true,
                        "is_soldout" => false,
                    ]
                )],
        ]);

        $mockHttpClient = TestUtils::createMockHttpClient($this, $mockBody);

        $client = new ZaiClient(self::CLIENT_ID, self::SECRET, array(), $mockHttpClient);

        // Test With ItemRequest
        $item = new Item("test_id", "test-item");
        $request = new ItemRequest("POST", [$item]);

        $response = $client->sendRequest($request);

        // To consume the stream, read the first 1024 bytes.
        // If you want to retrieve the entire contents of the stream, use getContents() instead of read().
        self::assertJsonStringEqualsJsonString(
            json_encode(json_decode($mockBody)->items),
            json_encode($response->getItems())
        );
    }

    public function testSendRequestWithCustomEndPoint()
    {
        $client = new ZaiClient(
            self::CLIENT_ID,
            self::SECRET,
            ["custom_endpoint" => "test-dev"],
            $this->mockHttpClient
        );

        // Test With ItemRequest
        $item_request = new ItemRequest(
            "POST", new Item("test_id", "test-item")
        );

        $item_endpoint = sprintf(
            $item_request->getBaseUrl(),
            $client->getOptions()['custom_endpoint']
        ) . $item_request->getPath(null);

        $rec_request = new GetUserRecommendation(
            "test_user_id",
            3
        );
        $rec_endpoint = sprintf(
            $rec_request->getBaseUrl(),
            $client->getOptions()['custom_endpoint']
        ) . $rec_request->getPath(self::CLIENT_ID);

        $event_request = new EventRequest(
            "test_user_id",
            ["test_item_id"],
            microtime(true),
            "test_event_type",
            ["test_event_value"],
            [null],
            [false]
        );

        $event_endpoint = sprintf(
            $event_request->getBaseUrl(),
            $client->getOptions()['custom_endpoint']
        ) . $event_request->getPath(null);

        self::assertSame("https://collector-api-test-dev.zaikorea.org/items", $item_endpoint);
        self::assertSame(
            "https://ml-api-test-dev.zaikorea.org/clients/test/recommenders/user-recommendations",
            $rec_endpoint
        );
        self::assertSame("https://collector-api-test-dev.zaikorea.org/events", $event_endpoint);
    }
}
