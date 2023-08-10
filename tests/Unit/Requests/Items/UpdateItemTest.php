<?php
namespace ZaiClient\Tests\Requests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Items\Item;
use ZaiClient\Requests\Items\UpdateItem;
use ZaiClient\Tests\TestUtils;

class UpdateItemTest extends TestCase
{

    public function testClassConstructorWithoutItemName()
    {
        $id = "Item_Id_1";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];
        $item = new Item($id, null, $properties);
        $update_item_request = new UpdateItem($item);

        $this->assertEquals("PUT", $update_item_request->getMethod());
        $this->assertEquals(Config::ITEMS_API_PATH, $update_item_request->getPath(null));
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                array_merge(
                    TestUtils::getEmptyItemRequestPayload(),
                    [
                        "item_id" => $id,
                        "category_id_1" => "Category_Id_1",
                        "is_active" => true,
                        "is_soldout" => false,
                    ]
                )
            ]),
            json_encode($update_item_request->getPayload())
        );
    }

    public function testClassConstructorWithItemName()
    {
        $id = "Item_Id_1";
        $name = "Test Item Name";
        $properties = [
            "item_name" => $name,
            "category_id_1" => "Category_Id_1",
        ];
        $item = new Item($id, $name, $properties);
        $update_item_request = new UpdateItem($item);

        $this->assertEquals("PUT", $update_item_request->getMethod());
        $this->assertEquals(Config::ITEMS_API_PATH, $update_item_request->getPath(null));
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                array_merge(
                    TestUtils::getEmptyItemRequestPayload(),
                    [
                        "item_id" => $id,
                        "item_name" => $name,
                        "category_id_1" => "Category_Id_1",
                        "is_active" => true,
                        "is_soldout" => false,
                    ]
                )
            ]),
            json_encode($update_item_request->getPayload())
        );
    }

    public function testClassConstructorWithNullItemId()
    {
        $this->expectException(InvalidArgumentException::class);

        $id = null;
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        new UpdateItem($id, $properties);
    }

    public function testClassConstructorWithItemId()
    {
        $this->expectException(InvalidArgumentException::class);
        $id = null;
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        new UpdateItem($id, $properties);
    }
}
