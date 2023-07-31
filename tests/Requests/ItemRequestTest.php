<?php
namespace ZaiClient\Tests\Requests;

use PHPUnit\Framework\TestCase;

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

        $expected_json = json_encode([
            "item_id" => $id,
            "item_name" => $name,
            "category_id_1" => null,
            "category_name_1" => null,
            "category_id_2" => null,
            "category_name_2" => null,
            "category_id_3" => null,
            "category_name_3" => null,
            "brand_id" => null,
            "brand_name" => null,
            "description" => null,
            "created_timestamp" => null,
            "updated_timestamp" => null,
            "is_active" => null,
            "is_soldout" => null,
            "promote_on" => null,
            "item_group" => null,
            "rating" => null,
            "price" => null,
            "click_counts" => null,
            "purchase_counts" => null,
            "image_url" => null,
            "item_url" => null,
            "miscellaneous" => null
        ]);

        $item_request = new ItemRequest($method, $id, $name, $properties);
        $payload_json = json_encode($item_request->get_payload());

        $this->assertJsonStringEqualsJsonString($expected_json, $payload_json);
        $this->assertSame(Config::ITEMS_API_PATH, $item_request->get_path(null));
    }

    function testClassConstructorWithProperties()
    {
        $method = "POST";
        $id = "Item_Id_1";
        $name = "Test Item Name";
        $properties = [
            "category_id_1" => "Category_Id_1",
        ];

        $expected_json = json_encode([
            "item_id" => $id,
            "item_name" => $name,
            "category_id_1" => "Category_Id_1",
            "category_name_1" => null,
            "category_id_2" => null,
            "category_name_2" => null,
            "category_id_3" => null,
            "category_name_3" => null,
            "brand_id" => null,
            "brand_name" => null,
            "description" => null,
            "created_timestamp" => null,
            "updated_timestamp" => null,
            "is_active" => null,
            "is_soldout" => null,
            "promote_on" => null,
            "item_group" => null,
            "rating" => null,
            "price" => null,
            "click_counts" => null,
            "purchase_counts" => null,
            "image_url" => null,
            "item_url" => null,
            "miscellaneous" => null
        ]);

        $item_request = new ItemRequest($method, $id, $name, $properties);

        $item_json = json_encode($item_request->get_payload());

        $this->assertJson(json_encode($item_json));
        $this->assertJsonStringEqualsJsonString($expected_json, $item_json);
        $this->assertSame(Config::ITEMS_API_PATH, $item_request->get_path(null));
    }
}
