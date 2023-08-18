<?php
namespace ZaiClient\Tests\Requests;

use PHPUnit\Framework\TestCase;

use ZaiClient\Tests\TestUtils;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Items\ItemRequest;




class ItemRequestTest extends TestCase
{
    public function testConstructor()
    {
        $payload = ["id1", "id2"];
        $item_request = new ItemRequest("POST", $payload);

        $this->assertEquals("POST", $item_request->getMethod());
        $this->assertEquals(Config::ITEMS_API_PATH, $item_request->getPath());
    }
}
