<?php
namespace ZaiClient\Tests\Requests;

use InvalidArgumentException;

use PHPUnit\Framework\TestCase;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Items\DeleteItem;
use ZaiClient\Tests\TestUtils;

class DeleteItemTest extends TestCase
{
    public function testClassConstructor()
    {
        $id = "Item_Id_1";

        $delete_item_request = new DeleteItem($id);

        $this->assertEquals("DELETE", $delete_item_request->getMethod());
        $this->assertEquals(Config::ITEMS_API_PATH, $delete_item_request->getPath(null));
        $this->assertEquals(["id" => array($id)], $delete_item_request->getQueryParams());
    }

    public function testClassConstructorWithEmptyItemId()
    {
        $this->expectException(InvalidArgumentException::class);

        $id = "";

        new DeleteItem($id); // NOSONAR
    }

    public function testClassConstructorWithItemIdOverMaxLen()
    {
        $this->expectException(InvalidArgumentException::class);
        $id = TestUtils::generateRandomString(504);

        new DeleteItem($id); // NOSONAR
    }
}
