<?php
namespace ZaiClient\Tests;

use PHPUnit\Framework\TestCase;

use ZaiClient\Requests\Items\AddItem;
use ZaiClient\Requests\Items\DeleteItem;
use ZaiClient\Requests\Items\Item;
use ZaiClient\Requests\Items\UpdateItem;
use ZaiClient\ZaiClient;

class ItemsSendRequestTest extends TestCase
{
    const CLIENT_ID = "test";
    const SECRET = 'KVPzvdHTPWnt0xaEGc2ix-eqPXFCdEV5zcqolBr_h1k';

    public function testAddItem()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $item = new Item("item_1", "test_item_name_1");
        $request = new AddItem($item);

        $response = $client->sendRequest($request, ['is_test' => true]);

        $expected_items = [
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    'item_id' => 'item_1',
                    'item_name' => 'test_item_name_1',
                    "is_active" => true,
                    "is_soldout" => false,
                ]
            )
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected_items),
            json_encode($response->getItems())
        );
    }

    public function testUpdateItem()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $client->sendRequest(
            new AddItem([
                new Item("item_1", "test_item_name_1"),
                new Item("item_2", "test_item_name_2"),
            ]),
            ['is_test' => true]
        );

        $response = $client->sendRequest(
            new UpdateItem([
                new Item("item_1", null, ["is_active" => false, "is_soldout" => true]),
                new Item("item_2", null, ["is_active" => false]),
            ]),
            ['is_test' => true]
        );

        $expected_items = [
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    'item_id' => 'item_1',
                    'item_name' => null,
                    "is_active" => false,
                    "is_soldout" => true,
                ]
            ),
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    'item_id' => 'item_2',
                    'item_name' => null,
                    "is_active" => false,
                    "is_soldout" => false,
                ]
            )
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected_items),
            json_encode($response->getItems())
        );

        $client->sendRequest(
            new DeleteItem(["item_1", "item_2"]),
            ['is_test' => true]
        );
    }

    public function testDeleteItem()
    {
        $client = new ZaiClient(self::CLIENT_ID, self::SECRET);
        $client->sendRequest(
            new AddItem([
                new Item("item_1", "test_item_name_1"),
                new Item("item_2", "test_item_name_2"),
            ]),
            ['is_test' => true]
        );
        $ids = ["item_1", "item_2"];
        $request = new DeleteItem($ids);

        $response = $client->sendRequest($request, ['is_test' => true]);

        $expected_items = [
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    'item_id' => 'item_1',
                    'item_name' => null,
                    "is_active" => null,
                    "is_soldout" => null,
                ]
            ),
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    'item_id' => 'item_2',
                    'item_name' => null,
                    "is_active" => null,
                    "is_soldout" => null,
                ]
                ),
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expected_items),
            json_encode($response->getItems())
        );
    }
}
