<?php
namespace ZaiClient\Requests\Items;

use ZaiClient\Requests\Items\ItemRequest;

class AddItem extends ItemRequest
{

    public function __construct($item_id, $item_name, $properties = array())
    {
        parent::__construct("POST", $item_id, $item_name, $properties);
    }
}
