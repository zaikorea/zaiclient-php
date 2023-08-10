<?php
namespace ZaiClient\Tests\Requests;

use PHPUnit\Framework\TestCase;

use ZaiClient\Tests\TestUtils;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Items\Item;

class ItemTest extends TestCase
{

    public function testConstructorWithEmptyProperties()
    {
        $id = "Item_Id_1";
        $name = "Test Item Name";
        $properties = [];

        $item = new Item($id, $name, $properties);

        $expected_json = json_encode(
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    "item_id" => $id,
                    "item_name" => $name,
                    "is_active" => true,
                    "is_soldout" => false,
                ]
            )
        );

        $this->assertJsonStringEqualsJsonString(
            $expected_json,
            json_encode($item)
        );
    }

    public function testConstructorWithIllegalProperties()
    {
        $id = "Item_Id_1";
        $name = "Test Item Name";
        $properties = [
            "category_id_1" => "Category_Id_1",
            "illegal_property" => "This should not be returned in getPayload()",
        ];

        $item = new Item($id, $name, $properties);

        $expected_json = json_encode(
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
        );

        $this->assertJsonStringEqualsJsonString(
            $expected_json,
            json_encode($item)
        );
    }
}


