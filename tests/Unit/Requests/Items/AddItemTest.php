<?php
namespace ZaiClient\Tests\Requests;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use ZaiClient\Configs\Config;
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

        $add_item_request = new AddItem($id, $name, $properties);

        $this->assertEquals("POST", $add_item_request->getMethod());
        $this->assertEquals(Config::ITEMS_API_PATH, $add_item_request->getPath(null));
        $this->assertEquals([], $add_item_request->getQueryParam());
        $this->assertJsonStringEqualsJsonString(
            json_encode(
                array_merge(
                    TestUtils::getEmptyItemRequestPayload(),
                    [
                        "item_id" => $id,
                        "item_name" => $name,
                        "category_id_1" => "Category_Id_1",
                    ]
                )
            ),
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
