<?php
namespace ZaiClient\Tests\Requests;

use PHPUnit\Framework\TestCase;

use ZaiClient\Tests\TestUtils;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Items\ItemRequest;




class ItemRequestTest extends TestCase
{
    function testClassConstructorWithEmptyProperties()
    {
        $method = "POST";
        $id = "Item_Id_1";
        $name = "Test Item Name";
        $properties = [];

        $expected_json = json_encode(
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    "item_id" => $id,
                    "item_name" => $name
                ]
            )
        );

        $item_request = new ItemRequest($method, $id, $name, $properties);
        $payload_json = json_encode($item_request->get_payload());

        $this->assertJsonStringEqualsJsonString($expected_json, $payload_json);
        $this->assertSame(Config::ITEMS_API_PATH, $item_request->get_path(null));
    }

    function testClassConstructorWithNoName()
    {
        $method = "POST";
        $id = "Item_Id_1";
        $name = null;
        $properties = [];

        $expected_json = json_encode(
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    "item_id" => $id,
                ]
            )
        );

        $item_request = new ItemRequest($method, $id, $name, $properties);

        $acutal_json = json_encode($item_request->get_payload());

        $this->assertJson(json_encode($acutal_json));
        $this->assertJsonStringEqualsJsonString($expected_json, $acutal_json);
    }

    function testClassConstructorWithProperties()
    {
        $method = "POST";
        $id = "Item_Id_1";
        $name = "Test Item Name";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        $expected_json = json_encode(
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    "item_id" => $id,
                    "item_name" => $name,
                    "category_id_1" => "Category_Id_1",
                ]
            )
        );

        $item_request = new ItemRequest($method, $id, $name, $properties);

        $acutal_json = json_encode($item_request->get_payload());

        $this->assertJson(json_encode($acutal_json));
        $this->assertJsonStringEqualsJsonString($expected_json, $acutal_json);
        $this->assertSame(Config::ITEMS_API_PATH, $item_request->get_path(null));
    }

    function testClassConstructorWithIllegalProperties()
    {
        $method = "POST";
        $id = "Item_Id_1";
        $name = "Test Item Name";
        $properties = [
            "category_id_1" => "Category_Id_1",
            "illegal_property" => "This should not be return in get_payload()",
        ];

        $expected_json = json_encode(
            array_merge(
                TestUtils::getEmptyItemRequestPayload(),
                [
                    "item_id" => $id,
                    "name" => $name,
                    "category_id_1" => "Category_Id_1",
                ]
            )
        );

        $item_request = new ItemRequest($method, $id, $name, $properties);

        $acutal_json = json_encode($item_request->get_payload());

        $this->assertJson(json_encode($acutal_json));
        $this->assertJsonStringNotEqualsJsonString($expected_json, $acutal_json);
    }

}
