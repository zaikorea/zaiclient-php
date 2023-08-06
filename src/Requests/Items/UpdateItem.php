<?php
namespace ZaiClient\Requests\Items;

use ZaiClient\Requests\Items\ItemRequest;

class UpdateItem extends ItemRequest
{
    public function __construct($id, $properties)
    {
        parent::__construct(
            "PUT",
            $id,
            $properties["item_name"] ?? null,
            $properties
        );
    }
}
