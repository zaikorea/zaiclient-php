<?php
namespace ZaiClient\Requests\Items;

use ZaiClient\Configs\Config;
use ZaiClient\http;
use ZaiClient\Requests\Items\Item;
use ZaiClient\Requests\Request;
use ZaiClient\ZaiClientException;

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

        $this->payload = new Item($properties);
    }


    public function get_payload($is_test = false)
    {

        return $this->payload;
    }


    public function get_path($client_id)
    {

        return config::ITEMS_API_PATH;
    }


    public function get_query_param()
    {

        return [];
    }
}
