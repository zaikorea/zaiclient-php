<?php
namespace ZaiClient\Tests\Requests;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Items\Item;
use ZaiClient\Requests\Items\AddItem;
use ZaiClient\Tests\TestUtils;

class AddItemTest extends TestCase
{
    public function testClassConstructor()
    {
        $id = "Item_Id_1";
        $name = "Test Item Name";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];
        $item = new Item($id, $name, $properties);
        $add_item_request = new AddItem($item);

        $this->assertEquals("POST", $add_item_request->getMethod());
        $this->assertEquals(Config::ITEMS_API_PATH, $add_item_request->getPath(null));
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
            json_encode($add_item_request->getPayload())
        );
    }

    public function testClassConstructorWithEmptyItemId()
    {
        $this->expectException(InvalidArgumentException::class);

        $id = "";
        $name = "Test Item Name";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        new AddItem($id, $name, $properties);
    }

    public function testClassConstructorWithItemIdOverMaxLength()
    {
        $this->expectException(InvalidArgumentException::class);

        $id = TestUtils::generateRandomString(2001);
        $name = "Test Item Name";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        new AddItem($id, $name, $properties);
    }

    public function testClassConstructorWithEmptyItemName()
    {
        $this->expectException(InvalidArgumentException::class);

        $id = "Item_Id_1";
        $name = "";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        new AddItem($id, $name, $properties);
    }
}
