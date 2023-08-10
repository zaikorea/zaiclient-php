<?php
namespace ZaiClient\Requests\Items;

use RuntimeException;
use ZaiClient\Configs\Config;
use ZaiClient\Requests\Items\Item;
use ZaiClient\Requests\Request;

class ItemRequest extends Request
{

    private $payload;

    /**
     * @param string $method
     * @param array[\ZaiClient\Requests\Items\Item]|array[string] $input
     */
    public function __construct(
        $method,
        $payload
    ) {

        parent::__construct($method, Config::COLLECTOR_API_ENDPOINT);
        $this->payload = $payload;
    }


    public function getPayload($is_test = null)
    {

        return $this->payload;
    }


    public function getPath($client_id = null)
    {

        return config::ITEMS_API_PATH;
    }


    public function getQueryParams()
    {

        throw new RuntimeException("NotImplementedError");
    }
}
