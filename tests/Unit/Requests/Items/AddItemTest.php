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
    const TEST_NAME = "Test_Item_Name";
    public function testClassConstructor()
    {
        $id = "Item_Id_1";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];
        $item = new Item($id, self::TEST_NAME, $properties);
        $add_item_request = new AddItem($item);

        $this->assertEquals("POST", $add_item_request->getMethod());
        $this->assertEquals(Config::ITEMS_API_PATH, $add_item_request->getPath(null));
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                array_merge(
                    TestUtils::getEmptyItemRequestPayload(),
                    [
                        "item_id" => $id,
                        "item_name" => self::TEST_NAME,
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
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        $item = new Item($id, self::TEST_NAME, $properties);
        new AddItem($item); // NOSONAR
    }

    public function testClassConstructorWithItemIdOverMaxLength()
    {
        $this->expectException(InvalidArgumentException::class);

        $id = TestUtils::generateRandomString(2001);
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        $item = new Item($id, self::TEST_NAME, $properties);

        new AddItem($item); // NOSONAR
    }

    public function testClassConstructorWithEmptyItemName()
    {
        $this->expectException(InvalidArgumentException::class);

        $id = "Item_Id_1";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        $item = new Item($id, null, $properties);

        new AddItem($item); // NOSONAR
    }
}
