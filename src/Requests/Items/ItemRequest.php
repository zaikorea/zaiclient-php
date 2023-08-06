<?php
namespace ZaiClient\Requests\Items;

use ZaiClient\Configs\Config;
use ZaiClient\Requests\Items\Item;
use ZaiClient\Requests\Request;

class ItemRequest extends Request
{

    private $payload;

    public function __construct(
        $method,
        $id,
        $name = null,
        $properties = []
    ) {

        parent::__construct($method, Config::COLLECTOR_API_ENDPOINT);

        $properties["item_id"] = $id;
        $properties["item_name"] = $name;

        $this->payload = new Item($properties); // All validation is done in Item class
    }


    public function getPayload($is_test = false)
    {

        return $this->payload;
    }


    public function getPath($client_id)
    {

        return config::ITEMS_API_PATH;
    }


    public function getQueryParam()
    {

        return [];
    }
}
