<?php

/**
 * EventLogTest
 * @author Uiseop Eom <tech@zaikorea.org>
 * @modifiedBy <name>
 */

namespace Zaikorea\ZaiClient\Tests;

use PHPUnit\Framework\TestCase;
use ZaiClient\ZaiClient\Tests\TestUtils;


class ItemRequestTest extends TestCase
{
    const CLIENT_ID = 'test';
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';
    private $zai_client;

    /**
     * @param ItemRequest $item_request
     */
    function checkSuccessfulItemAdd($item_request)
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


    /* --------------------- Test Purchase Event --------------------  */

    public function testAddSingleItem()
    {
        $function_name = __FUNCTION__;
        $item_id = $function_name . TestUtils::generateUuid();
        $item_name = $function_name . TestUtils::generateRandomString();
    }
}
