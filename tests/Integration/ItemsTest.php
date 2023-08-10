<?php
namespace ZaiClient\Tests;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Items\AddItem;
use ZaiClient\ZaiClient;

class ItemsSendRequestTest extends TestCase
{
    const CLIENT_ID = "test";
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';

    public function testAddItem()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);

        $request = new AddItem('item_1', 'test_item_name_1');

        $response = $client->sendRequest($request, ['is_test' => true]);

        $expected_items = [
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    'item_id' => 'item_1',
                    'item_name' => 'test_item_name_1',
                    "is_active" => false,
                    "is_soldout" => false,
                ]
            )
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected_items),
            json_encode($response->getItems())
        );
    }
}
