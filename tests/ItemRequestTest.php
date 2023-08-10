<?php

/**
 * EventLogTest
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */


namespace ZaiClient\Tests;

use PHPUnit\Framework\TestCase;

use ZaiClient\ZaiClient;
use ZaiClient\Tests\TestUtils;
use ZaiClient\Requests\AddItem;


class ItemRequestTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';
    private $zai_client;
    private $guzzle_client;

    protected function setUp(): void
    {
        $this->zai_client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $this->guzzle_client = new \GuzzleHttp\Client();
    }

    protected function tearDown(): void
    {
        $this->zai_client = null;
        $this->guzzle_client = null;
    }

    /**
     * @param ItemRequest $item_request
     */
    private function checkSuccessfulItemAdd($item_request)
    {
        $req_payload = $item_request->getPayload();

        $response = $this->zai_client->send_request($item_request);

        if (!isset($req_payload["is_active"]))
            $req_payload["is_active"] = true;

        if (!isset($req_payload["is_soldout"]))
            $req_payload["is_soldout"] = false;

        $res_items = $response->getItems();
        $res_counts = $response->getCount();

        self::assertSame(count($res_items), 1);
        self::assertSame($res_counts, 1);
        self::assertSame(json_decode($res_items), $req_payload);
    }
    /**
     * Our SDK doesn't support deleting events from the DB.
     * So we need to delete the event manually.
     * This is only for testing purpose.
     * @param ItemRequest $item_request
     */
    private function deleteItem($request)
    {
        $payload = $request->getPayload();

        $payload = [
            'item_id' => $payload['item_id']
        ];
        $url = $this->zai_client->collector_api_endpoint() . '/item';

        $response = $this->guzzle_client->delete($url, [
            'headers' => $this->zai_client->getHeaders(),
            'json' => $payload // this automatically sets the Content-Type header to application/json and uses the json as body
        ]);

        $response_body = json_decode($response->getBody(), true);
        return $response_body;
    }


    /* --------------------- Test Purchase Event --------------------  */

    public function testAddSingleItem()
    {
        $function_name = __FUNCTION__;
        $item_id = $function_name . TestUtils::generateUuid();
        $item_name = $function_name . TestUtils::generateRandomString();

        $add_item_request = AddItem($item_id, $item_name);

        $this->checkSuccessfulItemAdd($add_item_request);

        $response = $this->deleteItem($add_item_request);

        self::assertSame(1, $response['count']);
    }
}
